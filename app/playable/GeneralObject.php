<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Assignable.php';
require_once __DIR__.'/../components/Inspector.php';

use \game\GameObject;
use \components\Assignable;
use \components\Inspector;

/**
 * A general object to be used in game
 */
class GeneralObject extends GameObject
{
  public function __construct($name) {
    parent::__construct($name);
    $this->define(function ($key) {
      $assignable = new Assignable();
      $key->addComponent($assignable);
    });
    $this->define(function ($generalobject) {
      $inspector = new Inspector();
      $inspector->popEventHandler('inspect');
      $inspector->onInspect(function ($inspector) {
        return "You have something you don't know what it is.";
      });
      $generalobject->addComponent($inspector);
    });
  }
}
