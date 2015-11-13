<?php
namespace playable;

require_once 'GameObject.php';

require_once __DIR__.'/../components/Collider.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Openable.php';

use \components\Collider;
use \components\Inspector;
use \components\Lockable;
use \components\Openable;

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class Door extends GameObject
{

  public function __construct($direction)
  {
    $collider = new Collider($direction);
    $inspector = new Inspector();
    $openable = new Openable();
    $collider->onCollide(function ($collider) {
     $direction = $collider->getDirection();
     return "There is a door blocking your path to the $direction. You might try to open it first.";
    });
    $openable->onOpen(function ($openable) {
     $door = $openable->getParent();
     $collider = $door->getComponent('Collider');
     $collider->disableCollisions();
     $direction = $collider->getDirection();
     return "The door is now open. You may now enter through the $direction.";
    });
    $openable->onRefuseOpen(function ($openable) {
     return "You tried to open the door, but it will not budge.";
    });
    $openable->onClose(function ($openable) {
      $door = $openable->getParent();
      $collider = $door->getComponent('Collider');
      $collider->enableCollisions();
      $direction = $collider->getDirection();
      return "The door is now closed, blocking your path to the $direction.";
    });
    $openable->onRefuseClose(function ($openable) {
     return "You tried to close the door, but it will not budge.";
    });
    $inspector->onInspect(function ($inspector) {
     $door = $inspector->getParent();
     $collider = $door->getComponent('Collider');
     $openable = $door->getComponent('Openable');
     $direction = $collider->getDirection();
     if ($openable->isOpened())
       return "There is a open door to your $direction.";
     else
       return "There is a door blocking your path to the $direction.";
    });
    $this->addComponent($collider);
    $this->addComponent($inspector);
    $this->addComponent($openable);
  }
}
