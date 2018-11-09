<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

/**
 * Interface KickerBuilderInterface.
 *
 * @package Drupal\dennis_kicker
 */
interface KickerBuilderInterface {

  /**
   * Build the kicker.
   *
   * @param \Drupal\dennis_kicker\KickerInterface $kicker
   *   The kicker to build.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity being viewed.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The display object for the entity being viewed.
   * @param string $view_mode
   *   The view mode for the entity being viewed.
   */
  public function build(KickerInterface $kicker, ContentEntityInterface $entity, EntityViewDisplayInterface $display, $view_mode);

}
