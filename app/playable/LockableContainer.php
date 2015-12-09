<?php

namespace playable;

require_once "OpenableContainer.php";
require_once __DIR__.'/../components/Lockable.php';

use \components\Lockable;

class LockableContainer extends OpenableContainer
{
  public function __construct($name, $secret) {
    parent::__construct($name);
    //implement lockable
    $this->define(function ($lockableContainer) use ($name, $secret) {
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
      $lockableContainer->addComponent($lockable);
    });
    $this->define(function ($lockableContainer) use ($name) {
      $lockableContainer->getComponent('Inspector')->onInspect(function ($inspector) {
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
