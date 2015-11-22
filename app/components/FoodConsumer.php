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
    if ($this->trigger('beforeEat', array($this, $food))) {
      $this->hungry = false;
      $food->getContainer()->removeItem($food);
      return $this->trigger('eat', array($this, $food));
    }
    return $this->trigger('refuseEat', array($this, $food));
  }

  /**
   * @ignore
   */
  public function onBeforeEat($closure=null) {
    return $this->on("beforeEat", $closure);
  }

  /**
   * @ignore
   */
  public function onEat($closure=null) {
    return $this->on("eat", $closure);
  }

  /**
   * @ignore
   */
  public function onRefuseEat($closure=null) {
    return $this->on("refuseEat", $closure);
  }
}
