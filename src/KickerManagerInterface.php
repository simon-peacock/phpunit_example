<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

/**
 * Interface KickerManagerInterface.
 *
 * @package Drupal\dennis_kicker
 */
interface KickerManagerInterface {

  /**
   * Adds another kicker builder.
   *
   * @param \Drupal\dennis_kicker\KickerBuilderInterface $builder
   *   The kicker builder to add.
   * @param int $priority
   *   Priority of the kicker builder.
   */
  public function addBuilder(KickerBuilderInterface $builder, $priority);

  /**
   * Provides the kicker to be built.
   *
   * @param \Drupal\dennis_kicker\KickerInterface $kicker
   *   The Kicker.
   *
   * @return self
   *   This kicker manager.
   */
  public function setKicker(KickerInterface $kicker);

  /**
   * Build the kicker.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity being viewed.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The display object for the entity.
   * @param string $view_mode
   *   The view mode.
   *
   * @return \Drupal\dennis_kicker\KickerInterface
   *   The kicker.
   */
  public function buildKicker(ContentEntityInterface $entity, EntityViewDisplayInterface $display, $view_mode);

}
