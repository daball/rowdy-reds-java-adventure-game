<?php

namespace playable;

require_once 'GameObject.php';
require_once __DIR__.'/../components/Assignable.php';
require_once __DIR__.'/../components/Inspector.php';
// require_once 'IAssignable.php';
// require_once 'IEdidable.php';
// require_once 'TCreate.php';
// require_once 'TAssignable.php';

use components\Assignable;
use components\Inspector;

/**
 * A Food item is used to feed a hungy Dog obstacle.
 */
class Food extends GameObject //implements IInspectable, IAssignable, IEdidable, \Serializable
{
  // use TCreate;
  // use TAssignable;

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

  // public function serialize() {
  //   return serialize(
  //     array(
  //       'description' => $this->description,
  //     )
  //   );
  // }
  //
  // public function unserialize($data) {
  //   $data = unserialize($data);
  //   $this->__construct();
  //   $this->description = $data['description'];
  // }
}
