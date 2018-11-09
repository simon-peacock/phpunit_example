<?php

namespace Drupal\dennis_kicker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

/**
 * Class KickerManager.
 *
 * @package Drupal\dennis_kicker
 */
class KickerManager implements KickerManagerInterface {

  /**
   * Holds arrays of kicker builders, keyed by priority.
   *
   * @var array
   */
  protected $builders = [];

  /**
   * Holds the array of kicker builders sorted by priority.
   *
   * Set to NULL if the array needs to be re-calculated.
   *
   * @var \\Drupal\dennis_kicker\KickerBuilderInterface|null
   */
  protected $sortedBuilders;

  /**
   * The kicker to build.
   *
   * @var \Drupal\dennis_kicker\KickerInterface
   */
  protected $kicker;

  /**
   * KickerBuilder constructor.
   *
   * @param \Drupal\dennis_kicker\KickerInterface $kicker
   *   The kicker to build.
   */
  public function __construct(KickerInterface $kicker) {
    $this->setKicker($kicker);
  }

  /**
   * {@inheritdoc}
   */
  public function setKicker(KickerInterface $kicker) {
    $this->kicker = $kicker;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function addBuilder(KickerBuilderInterface $builder, $priority) {
    $this->builders[$priority][] = $builder;
    // Force the builders to be re-sorted.
    $this->sortedBuilders = NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function buildKicker(ContentEntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    // Ensure a fresh kicker to start with.
    $this->kicker->reset();

    // Call the build method of registered kicker builders,
    // until one of them returns the kicker.
    foreach ($this->getSortedBuilders() as $builder) {
      $builder->build($this->kicker, $entity, $display, $view_mode);
      if ($this->kicker->built()) {
        return $this->kicker;
      }
    }

    // Return an unbuilt kicker.
    return $this->kicker->reset();
  }

  /**
   * Returns the sorted array of kicker builders.
   *
   * @return KickerBuilderInterface[]
   *   An array of kicker builder objects.
   */
  protected function getSortedBuilders() {
    if (!isset($this->sortedBuilders)) {
      // Sort the builders according to priority.
      krsort($this->builders);
      // Merge nested builders from $this->builders into $this->sortedBuilders.
      $this->sortedBuilders = [];
      foreach ($this->builders as $builders) {
        $this->sortedBuilders = array_merge($this->sortedBuilders, $builders);
      }
    }
    return $this->sortedBuilders;
  }

}
