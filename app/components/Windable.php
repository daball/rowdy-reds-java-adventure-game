<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../game/Player.php';

use \game\Player;

/**
 * @author David Ball
 * @ignore
 **/
class Windable extends BaseComponent
{
  /**
   * @ignore
   **/
  protected $wound = false;

  /**
   * @ignore
   */
  public function __construct() {
    $this->define(function ($windable) {
      $windable->onWind(function ($windable) {
        $gameObject = $windable->getParent()->getName();
        return "You wind the $gameObject.";
      });
      $windable->onUnwind(function ($windable) {
        $gameObject = $windable->getParent()->getName();
        return "You unwind the $gameObject.";
      });
    });
  }

  /**
   * @ignore
   */
  public function isWound() {
    return $this->wound;
  }

  /**
   * @ignore
   */
  public function wind() {
    $this->wound = true;
    return $this->trigger('wind', array($this));
  }

  /**
   * @ignore
   */
  public function unwind() {
    $this->wound = false;
    return $this->trigger('unwind', array($this));
  }

  /**
   * @ignore
   */
  public function onWind($closure=null) {
    return $this->on('wind', $closure);
  }

  /**
   * @ignore
   */
  public function onUnwind($closure=null) {
    return $this->on('unwind', $closure);
  }
}
