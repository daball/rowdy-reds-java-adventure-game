<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../game/Direction.php';

use \game\Direction;

/**
 * The Collider component works together with the player when navigating
 * to detect collisions with objects. Add this component to your GameObjects
 * that you want the player to collide with in a particular direction of
 * navigation.
 * @author David Ball
 * @ignore
 **/
class Collider extends BaseComponent
{
  /**
   * @ignore
   */
  protected $direction = "";

  /**
   * @ignore
   **/
  protected $enabled = true;

  /**
   * @ignore
   */
  public function __construct($direction) {
    $this->define(function ($collider) use ($direction) {
      $collider->setDirection($direction);
      $collider->onBeforeCollide(function ($collider, $direction) {
        return $collider->isEnabled() && $collider->validateCollision($direction);
      });
      $collider->onCollide(function ($collider, $direction) {
        $direction = $collider->getDirection();
        return "There is something in the way to your $direction.";
      });
    });
  }

  /**
   * @ignore
   */
  public function validateCollision($direction) {
    return ($this->isEnabled() && Direction::fullDirection($direction) == $this->direction);
  }

  /**
   * @ignore
   */
  public function getDirection() {
    return $this->direction;
  }

  /**
   * @ignore
   */
  public function setDirection($direction) {
    $this->direction = Direction::fullDirection($direction);
    return $this->getDirection();
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
  public function collide($direction) {
    $collisionEvent = false;
    $direction = Direction::fullDirection($direction);
    if ($this->trigger('beforeCollide', array($this, $direction)))
      $collisionEvent = $this->trigger('collide', array($this, $direction));
    return $collisionEvent;
  }

  /**
   * @ignore
   */
  public function onBeforeCollide($closure=null) {
    return $this->on("beforeCollide", $closure);
  }

  /**
   * @ignore
   */
  public function onCollide($closure=null) {
    return $this->on("collide", $closure);
  }
}
