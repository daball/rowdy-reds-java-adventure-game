<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Collider.php';
require_once __DIR__.'/../components/FoodConsumer.php';

use \components\Collider;
use \components\FoodConsumer;
use \game\GameObject;

/**
 * Dog obstacle.
 */
class Dog extends GameObject
{
  public function __construct($name, $direction)
  {
    parent::__construct($name);
    $this->define(function ($dog) use ($direction) {
      $inspector = $dog->getComponent('Inspector');
      $inspector->onInspect(function ($inspector) {
        $dog = $inspector->getParent();
        $foodConsumer = $dog->getComponent('FoodConsumer');
        if ($foodConsumer->isHungry()) {
          return "You have found a growling dog blocking your path.";
        }
        else {
          return "The dog is now happily eating from his bowl.";
        }
      });
      $dog->addComponent((new Collider($direction))->define(function ($collider) {
        $collider->onCollide(function () {
          return "The dog growls menacingly.";
        });
      }));
      $dog->addComponent((new FoodConsumer())->define(function ($foodConsumer) {
        $foodConsumer->onEat(function () {
          $dog = $inspector->getParent();
          $collider = $dog->getComponent('Collider');
          $collider->disableCollisions();
          return "The dog is now happily eating from his bowl.";
        });
      }));
    });
  }
}