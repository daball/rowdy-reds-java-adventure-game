<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Assignable.php';
require_once __DIR__.'/../components/Inspector.php';

use \game\GameObject;
use \components\Assignable;
use \components\Inspector;

/**
 * A Food item is used to feed a hungy Dog obstacle.
 */
class Food extends GameObject
{
  public function __construct($name) {
    parent::__construct($name);
    $this->define(function ($key) {
      $assignable = new Assignable();
      $key->addComponent($assignable);
    });
    $this->define(function ($food) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        return "You have found food.";
      });
      $food->addComponent($inspector);
    });
  }
}
