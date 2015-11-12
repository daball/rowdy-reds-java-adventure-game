<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../map/Direction.php';

use \map\Direction;

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
  protected $onCollideCallback = null;

  /**
   * @ignore
   */
  public function __construct($direction) {
    $this->setDirection($direction);
    $this->onCollide(function ($collider) {
      $direction = $collider->getDirection();
      return "There is something in the way to your $direction.";
    });
  }

  /**
   * @ignore
   */
  public function validateCollision($direction) {
    return ($this->enabled && Direction::fullDirection($direction) == $direction);
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
  public function collisionsEnabled() {
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
  public function collisionsDisabled() {
    return !$this->enabled;
  }

  /**
   * @ignore
   */
  public function collide() {
    $collision = "";
    $cb = $this->onCollideCallback;
    if ($this->enabled && $cb)
      $collision = $cb($this);
    return $collision;
  }

  /**
   * @ignore
   */
  public function onCollide($callback) {
    $this->onCollideCallback = $callback;
  }
}
