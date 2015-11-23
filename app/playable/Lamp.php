<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';

use \game\GameObject;

/**
 * A Lamp item is used to light dark areas.
 */
class Lamp extends GameObject
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->define(function ($lamp) {
      $lamp->addComponent(new Assignable());
      $inspector = $lamp->getComponent('Inspector');
      $inspector->popEventHandler('inspect');
      $inspector->onInspect(function ($inspector) {
        return setDescription("You found a lamp.");
      });
    });
  }
}
