<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class ContentPartnershipKickerBuilder.
 *
 * @package Drupal\dennis_kicker
 */
class ContentPartnershipKickerBuilder implements KickerBuilderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * ContentPartnershipKickerBuilder constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function build(KickerInterface $kicker, ContentEntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    // If it is tagged with a term from the Content Partnerships taxonomy,
    // then that term is displayed.
    if ($entity->hasField('field_sponsored')) {
      if ($reference = $entity->get('field_sponsored')->first()) {
        $term = $this->entityTypeManager->getStorage('taxonomy_term')
          ->load($reference->getString());
        if ($term) {
          // A Content Partnerships term,
          // then use it as the kicker but with no path.
          $kicker
            ->setEntity($term)
            ->setText($term->getName())
            ->setBuilt();
        }
      }
    }
  }

}
