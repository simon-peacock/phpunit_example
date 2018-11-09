<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Render\RenderableInterface;
use Drupal\Core\Url;

/**
 * Interface KickerInterface.
 *
 * @package Drupal\dennis_kicker
 */
interface KickerInterface extends RenderableInterface {

  /**
   * Whether the kicker is ready to be used.
   *
   * @return bool
   *   True if the kicker has been built.
   */
  public function built();

  /**
   * Mark the kicker as ready to use.
   *
   * @param bool $bool
   *   Whether the kicker is ready to be used.
   *
   * @return self
   *   This kicker object.
   */
  public function setBuilt($bool = TRUE);

  /**
   * Where the kicker links to.
   *
   * @return \Drupal\Core\Url
   *   The URL object.
   */
  public function getUrl();

  /**
   * Set where the kicker links to.
   *
   * @param \Drupal\Core\Url $url
   *   A URL object.
   *
   * @return self
   *   This kicker object.
   */
  public function setUrl(Url $url);

  /**
   * The string shown in the kicker.
   *
   * @return string
   *   The kicker text.
   */
  public function getText();

  /**
   * The entity to use as the kicker.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The term used in the kicker.
   */
  public function getEntity();

  /**
   * The text to us in the kicker.
   *
   * @param string $text
   *   The text to use in the kicker.
   *
   * @return self
   *   This kicker object.
   */
  public function setText($text);

  /**
   * Set the entity to use as the kicker.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to use for the kicker.
   *
   * @return self
   *   This kicker object.
   */
  public function setEntity(EntityInterface $entity);

  /**
   * Reset to the default state.
   *
   * @return self
   *   This kicker object.
   */
  public function reset();

}
