<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../game/Direction.php';

use \game\Direction;

/**
 * The FoodConsumer can be assigned to any GameObject that can consume Food.
 * @author David Ball
 * @ignore
 **/
class FoodConsumer extends BaseComponent
{
  /**
   * @ignore
   */
  protected $isHungry = true;

  /**
   * @ignore
   */
   protected $onBeforeEat = null;
   protected $onEat = null;
   protected $onRefuseEat = null;

  /**
   * @ignore
   */
  public function __construct() {
    $this->define(function ($foodConsumer) {
      $foodConsumer->setHungry(true);
      $foodConsumer->onBeforeEat(function ($foodConsumer, $food) {
        return (is_a($food, "\playable\Food"));
      });
      $foodConsumer->onEat(function ($foodConsumer, $food) {
        return "The " . $foodConsumer->getParent()->getName() . " ate the " . $food->getName() . ".";
      });
      $foodConsumer->onRefuseEat(function ($foodConsumer, $food) {
        return "The " . $foodConsumer->getParent()->getName() . " did not eat the " . $food->getName() . ".";
      });
    });
  }

  /**
   * @ignore
   */
  public function validateCollision($direction) {
    return ($this->enabled && Direction::fullDirection($direction) == $this->direction);
  }

  /**
   * @ignore
   */
  public function isHungry() {
    return $this->isHungry;
  }

  /**
   * @ignore
   */
  public function setHungry($isHungry) {
    $this->isHungry = $isHungry;
    return $this->isHungry();
  }

  /**
   * @ignore
   */
  public function enableCollisions() {
    $this->enabled = true;
  }

  /**
   * @ignore
   */
  public function isEnabled() {
    return $this->enabled;
  }

  /**
   * @ignore
   */
  public function disableCollisions() {
    $this->enabled = false;
  }

  /**
   * @ignore
   */
  public function eat($food) {
    $onBeforeEat = $this->onBeforeEat();
    $onEat = $this->onEat();

    if ($onBeforeEat($this, $food)) {
      $this->hungry = false;
      $food->getContainer()->removeItem($food);
      return $onEat($this, $food);
    }
    return $onRefuseEat($this, $food);
  }

  /**
   * @ignore
   */
  public function onBeforeEat($closure=null) {
    if ($closure)
      $this->onBeforeEat = $this->serializableClosure($closure);
    return $this->onBeforeEat;
  }

  /**
   * @ignore
   */
  public function onEat($closure=null) {
    if ($closure)
      $this->onEat = $this->serializableClosure($closure);
    return $this->onEat;
  }

  /**
   * @ignore
   */
  public function onRefuseEat($closure=null) {
    if ($closure)
      $this->onRefuseEat = $this->serializableClosure($closure);
    return $this->onRefuseEat;
  }
}
