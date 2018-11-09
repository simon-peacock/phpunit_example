<?php

namespace Drupal\dennis_kicker\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dennis_kicker\KickerManagerInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayFormattedBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Kicker Extra field.
 *
 * @ExtraFieldDisplay(
 *   id = "kicker",
 *   label = @Translation("Kicker"),
 *   bundles = {
 *     "node.*",
 *   }
 * )
 */
class Kicker extends ExtraFieldDisplayFormattedBase implements ContainerFactoryPluginInterface {

  /**
   * The service that builds the render array.
   *
   * @var \Drupal\dennis_kicker\KickerManagerInterface
   */
  protected $manager;

  /**
   * Constructor for the Kicker plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\dennis_kicker\KickerManagerInterface $manager
   *   The Kicker manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, KickerManagerInterface $manager) {
    $this->manager = $manager;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dennis_kicker.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(ContentEntityInterface $entity) {
    return $this->manager->buildKicker(
      $entity,
      $this->getEntityViewDisplay(),
      $this->getViewMode())
      ->toRenderable();
  }

}
