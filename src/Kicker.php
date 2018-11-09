<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Class Kicker.
 *
 * @package Drupal\dennis_kicker
 */
class Kicker implements KickerInterface {

  use RefinableCacheableDependencyTrait;

  /**
   * The entity used for the kicker.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   *   The entity object.
   */
  protected $entity;

  /**
   * The text to use when displaying the kicker.
   *
   * @var string
   *   The text.
   */
  protected $text;

  /**
   * The url for generating a link.
   *
   * @var \Drupal\Core\Url
   *   The Url object.
   */
  protected $url;

  /**
   * Whether the kicker has been built.
   *
   * @var bool
   *   True if built.
   */
  protected $built = FALSE;

  /**
   * {@inheritdoc}
   */
  public function built() {
    return $this->built;
  }

  /**
   * {@inheritdoc}
   */
  public function setBuilt($bool = TRUE) {
    $this->built = $bool;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setUrl(Url $url) {
    $this->url = $url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUrl() {
    if ($this->url instanceof Url) {
      return $this->url;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getText() {
    return $this->text;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    if ($this->entity instanceof EntityInterface) {
      return $this->entity;
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setText($text) {
    $this->text = $text;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntity(EntityInterface $entity) {
    $this->entity = $entity;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    if ($entity = $this->getEntity()) {
      // If a term changes, then the cache should be invalidated.
      $this->addCacheTags($entity->getCacheTags());
    }

    return $this->cacheTags;
  }

  /**
   * {@inheritdoc}
   */
  public function toRenderable() {
    if (!$this->built()) {
      // Nothing to render.
      return [];
    }

    if (empty($this->getText())) {
      // Nothing to render.
      return [];
    }

    $build = [];
    if ($this->getEntity()) {
      $build = [
        '#cache' => [
          'contexts' => $this->getCacheContexts(),
          'tags' => $this->getCacheTags(),
          'max-age' => $this->getCacheMaxAge(),
        ],
      ];
    }

    if (empty($this->getUrl())) {
      // Text only.
      $build += [
        '#markup' => $this->getText(),
      ];
    }
    else {
      $build += [
        '#type' => 'link',
        '#title' => $this->getText(),
        '#url' => $this->getUrl(),
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    $this->url = NULL;
    $this->text = NULL;
    $this->entity = NULL;
    $this->built = FALSE;

    return $this;
  }

}
