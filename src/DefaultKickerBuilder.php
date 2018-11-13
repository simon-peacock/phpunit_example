<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\ParamConverter\ParamNotConvertedException;
use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\Routing\RouteMatch;
use Drupal\term_node\NodeResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

/**
 * Class DefaultKickerBuilder.
 *
 * @package Drupal\dennis_kicker
 */
class DefaultKickerBuilder implements KickerBuilderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The dynamic router service.
   *
   * @var \Symfony\Component\Routing\Matcher\RequestMatcherInterface
   */
  protected $router;

  /**
   * The inbound path processor.
   *
   * @var \Drupal\Core\PathProcessor\InboundPathProcessorInterface
   */
  protected $pathProcessor;

  /**
   * The service from term_node to get the tid of the referencing term.
   *
   * @var \Drupal\term_node\NodeResolverInterface
   */
  protected $nodeResolver;

  /**
   * DefaultKickerBuilder constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Symfony\Component\Routing\Matcher\RequestMatcherInterface $router
   *   The dynamic router service.
   * @param \Drupal\Core\PathProcessor\InboundPathProcessorInterface $path_processor
   *   The inbound path processor.
   * @param \Drupal\term_node\NodeResolverInterface $node_resolver
   *   The term_node resolver.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    RequestMatcherInterface $router,
    InboundPathProcessorInterface $path_processor,
    NodeResolverInterface $node_resolver) {
    $this->entityTypeManager = $entity_type_manager;
    $this->router = $router;
    $this->pathProcessor = $path_processor;
    $this->nodeResolver = $node_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public function build(KickerInterface $kicker, ContentEntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    // The term used for the kicker should follow the same logic
    // that we use for the breadcrumb/paths.
    // It is the last entity that is in the path that should be used.
    // NB: Cannot use the breadcrumb as it always builds the current page.
    $path_elements = explode('/', $entity->toUrl()->toString());
    while (count($path_elements) > 1) {
      array_pop($path_elements);
      // Build a request for the path.
      $route_request = $this->getRequestForPath(implode('/', $path_elements));
      if ($route_request) {
        $route_match = RouteMatch::createFromRequest($route_request);
        foreach ($route_match->getParameters() as $entity) {
          if ($entity instanceof EntityInterface) {

            // If the entity is a node, it may be a term node,
            // in which case we want to use the term's label not the node's.
            if ($entity->getEntityTypeId() == 'node' && $tid = $this->nodeResolver->getReferencedBy($entity->id())) {
              $entity = $this->entityTypeManager->getStorage('taxonomy_term')
                ->load($tid);
            }

            $kicker->setEntity($entity)
              ->setText($entity->label())
              ->setUrl($entity->toUrl())
              ->setBuilt();
            // Found the last entity, so do no more.
            return;
          }
        }
      }
    }
  }

  /**
   * Matches a path in the router.
   *
   * Shameless copy of core/modules/system/src/PathBasedBreadcrumbBuilder.php.
   *
   * @param string $path
   *   The request path with a leading slash.
   *
   * @return \Symfony\Component\HttpFoundation\Request
   *   A populated request object or NULL if the path couldn't be matched.
   */
  protected function getRequestForPath($path) {
    $request = Request::create($path);
    // Performance optimization: set a short accept header to reduce overhead in
    // AcceptHeaderMatcher when matching the request.
    $request->headers->set('Accept', 'text/html');
    // Find the system path by resolving aliases, language prefix, etc.
    $processed = $this->pathProcessor->processInbound($path, $request);
    if (empty($processed) || !empty($exclude[$processed])) {
      // This resolves to the front page.
      return NULL;
    }

    // Attempt to match this path to provide a fully built request.
    try {
      $request->attributes->add($this->router->matchRequest($request));
      return $request;
    }
    catch (ParamNotConvertedException $e) {
      return NULL;
    }
    catch (ResourceNotFoundException $e) {
      return NULL;
    }
    catch (MethodNotAllowedException $e) {
      return NULL;
    }
    catch (AccessDeniedHttpException $e) {
      return NULL;
    }
  }

}
