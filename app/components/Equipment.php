<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../game/Player.php';

use \game\Player;

/**
 * @author David Ball
 * @ignore
 **/
class Equipment extends BaseComponent
{
  /**
   * @ignore
   **/
  protected $equipped = false;

  /**
   * @ignore
   */
  public function __construct($direction) {
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
  public function equip() {
    $collisionEvent = false;
    $direction = Direction::fullDirection($direction);
    if ($this->trigger('beforeCollide', array($this, $direction)))
      $collisionEvent = $this->trigger('collide', array($this, $direction));
    return $collisionEvent;
  }

  /**
   * @ignore
   */
  public function onCollide($closure=null) {
    return $this->on("equip", $closure);
  }
}
