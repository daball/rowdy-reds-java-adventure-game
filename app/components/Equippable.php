<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../game/Player.php';

use \game\Player;

/**
 * @author David Ball
 * @ignore
 **/
class Equippable extends BaseComponent
{
  /**
   * @ignore
   **/
  protected $equipped = false;

  /**
   * @ignore
   */
  public function __construct() {
    $this->define(function ($equippable) {
      $equippable->onEquip(function ($equippable) {
        $gameObject = $equippable->getParent()->getName();
        return "You have equipped the $gameObject.";
      });
    });
  }

  /**
   * @ignore
   */
  public function isEquipped() {
    return $this->equipped;
  }

  /**
   * @ignore
   */
  public function equip() {
    $this->equipped = true;
    return $this->trigger('equip', array($this));
  }

  /**
   * @ignore
   */
  public function onEquip($closure=null) {
    return $this->on('equip', $closure);
  }
}
