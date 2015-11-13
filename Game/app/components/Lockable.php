<?php

namespace components;

require_once 'BaseComponent.php';
require_once __DIR__.'/../playable/Key.php';

use \playable\Key;

/**
 * The Lockable
 * @author David Ball
 * @ignore
 **/
class Lockable extends BaseComponent
{
  protected $locked = true;
  protected $key = null;

  protected $onBeforeLockCallback = null;
  protected $onLockCallback = null;
  protected $onRefuseLockCallback = null;

  protected $onBeforeUnlockCallback = null;
  protected $onUnlockCallback = null;
  protected $onRefuseUnlockCallback = null;

  public function __construct(Key $key) {
    $this->key = $key;

    //This defines the logic for locking/unlocking an object
    $lockLogic = function ($lockable, $keyProvided) {
      return ($lockable->getKey()->getKey() == $keyProvided->getKey());
    };
    $this->onBeforeLock($lockLogic);
    $this->onBeforeUnlock($lockLogic);

    $this->onLock(function ($lockable, $keyProvided) {
      return "You turned the key and the object locks.";
    });
    $this->onRefuseLock(function ($lockable, $keyProvided) {
      return "You insert the key, but it doesn't fit the lock.";
    });
    $this->onUnlock(function ($lockable, $keyProvided) {
      return "You turned the key and the object unlocks.";
    });
    $this->onRefuseUnlock(function ($lockable, $keyProvided) {
      return "You insert the key, but it doesn't fit the lock.";
    });
  }

  /* Property Getter/Setter */

  public function getKey() {
    return $this->key;
  }

  public function setLocked() {
    $this->locked = true;
  }

  public function setUnlocked() {
    $this->locked = false;
  }

  /* Public API for Component */

  public function lock(Key $key) {
    $onBeforeLockCallback = $this->onBeforeLockCallback;
    $onLockCallback = $this->onLockCallback;
    $onRefuseLockCallback = $this->onRefuseLockCallback;

    if ($onBeforeLockCallback($this, $key)) {
      $this->setLocked();
      return $onLockCallback($this, $key);
    }
    else
      return $onRefuseLockCallback($this, $key);
  }

  public function unlock(Key $key) {
    $onBeforeUnlockCallback = $this->onBeforeUnlockCallback;
    $onUnlockCallback = $this->onUnlockCallback;
    $onRefuseUnlockCallback = $this->onRefuseUnlockCallback;

    if ($onBeforeUnlockCallback($this, $key)) {
      $this->setUnlocked();
      return $onUnlockCallback($this, $key);
    }
    else
      return $onRefuseUnlockCallback($this, $key);
  }

  public function isLocked() {
    return $this->locked;
  }

  public function isUnlocked() {
    return !$this->isLocked();
  }

  /* Event Callback Registration Functions */

  public function onBeforeLock($callback) {
    $this->onBeforeLockCallback = $callback;
  }

  public function onLock($callback) {
    $this->onLockCallback = $callback;
  }

  public function onRefuseLock($callback) {
    $this->onRefuseLockCallback = $callback;
  }

  public function onBeforeUnlock($callback) {
    $this->onBeforeUnlockCallback = $callback;
  }

  public function onUnlock($callback) {
    $this->onUnlockCallback = $callback;
  }

  public function onRefuseUnlock($callback) {
    $this->onRefuseUnlockCallback = $callback;
  }
}
