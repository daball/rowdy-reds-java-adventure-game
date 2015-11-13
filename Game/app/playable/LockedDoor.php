<?php

namespace playable;

require_once "Door.php";
require_once "Key.php";
require_once __DIR__.'/../components/Lockable.php';

use \components\Lockable;

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class LockedDoor extends Door {
  public function __construct($direction, $key) {
    parent::__construct($direction);

    //add lockable component
    $lockable = new Lockable($key);
    $lockable->onUnlock(function ($lockable, $keyProvided) {
      $door = $lockable->getParent();
      $collider = $door->getComponent('Collider');
      $direction = $collider->getDirection();
      $openable = $door->getComponent('Openable');
      $openable->open();
      return "You unlocked the door to the $direction, and it swings open.";
    });
    $lockable->onLock(function ($lockable, $keyProvided) {
      $door = $lockable->getParent();
      $collider = $door->getComponent('Collider');
      $direction = $collider->getDirection();
      $openable = $door->getComponent('Openable');
      $openable->open();
      return "You closed and locked the door, and it is now blocking your way to the $direction.";
    });
    $this->addComponent($lockable);

    //override Door
    $this->getComponent('Inspector')->onInspect(function ($inspector) {
      $door = $inspector->getParent();
      $lockable = $door->getComponent('Lockable');
      $openable = $door->getComponent('Openable');
      $collider = $door->getComponent('Collider');
      $direction = $collider->getDirection();
      if ($lockable->isLocked()) {
        return "There is a locked door to your $direction.";
      }
      else {
        if ($openable->isOpened())
          return "There is an unlocked door to your $direction.";
        else
          return "There is an unlocked door to your $direction, but it is closed.";
      }
    });
  }
}
