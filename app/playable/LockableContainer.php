<?php

namespace playable;

require_once "OpenableContainer.php";

class LockableContainer extends OpenableContainer
{
  public function __construct($name, $secret) {
    parent::__construct($name);
    //implement lockable
    $this->define(function ($unlockableContainer) use ($name, $secret) {
      $lockable = new Lockable($secret);
      $lockable->onLock(function ($lockable) {
        return "You turn the key and the container locks.";
      });
      $lockable->onRefuseLock(function ($lockable) {
        return "You turn the key and nothing happens.";
      });
      $lockable->onUnlock(function ($lockable) {
        return "You turn the key and the container unlocks.";
        //You see inside: ... ...
      });
      $lockable->onRefuseUnlock(function ($lockable) {
        return "You turn the key and nothing happens.";
      });
      $unlockableContainer->addComponent($lockable);
    });
    //override Door
    $this->define(function ($lockedDoor) use ($name, $direction, $key) {
      $lockedDoor->getComponent('Inspector')->onInspect(function ($inspector) {
        $door = $inspector->getParent();
        $lockable = $door->getComponent('Lockable');
        $openable = $door->getComponent('Openable');
        if ($lockable->isLocked()) {
          return "This is a locked container.";
        }
        else {
          if ($openable->isOpened())
            return "This is an unlocked container.";
          else
            return "This is an unlocked container, but it is closed.";
        }
      });
    });
  }
}
