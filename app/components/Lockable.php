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

  protected $onBeforeLockClosure = null;
  protected $onLockClosure = null;
  protected $onRefuseLockClosure = null;

  protected $onBeforeUnlockClosure = null;
  protected $onUnlockClosure = null;
  protected $onRefuseUnlockClosure = null;

  public function __construct(Key $key) {
    $this->define(function ($lockable) use ($key) {
      $lockable->setKey($key);
      //This defines the logic for locking/unlocking an object
      $lockLogic = function ($lockable, $keyProvided) {
        return ($lockable->getKey()->getSecret() == $keyProvided->getSecret());
      };
      $lockable->onBeforeLock($lockLogic);
      $lockable->onBeforeUnlock($lockLogic);

      $lockable->onLock(function ($lockable, $keyProvided) {
        if ($lockable->getParent()
          && $lockable->getParent()->hasComponent('Openable'))
          $lockable->getParent()->getComponent('Openable')->close();
        return "You turned the key and the object locks.";
      });
      $lockable->onRefuseLock(function ($lockable, $keyProvided) {
        return "You insert the key, but it doesn't fit the lock.";
      });
      $lockable->onUnlock(function ($lockable, $keyProvided) {
        if ($lockable->getParent()
          && $lockable->getParent()->hasComponent('Openable'))
          $lockable->getParent()->getComponent('Openable')->open();
        return "You turned the key and the object unlocks.";
      });
      $lockable->onRefuseUnlock(function ($lockable, $keyProvided) {
        return "You insert the key, but it doesn't fit the lock.";
      });
    });
  }

  /* Property Getter/Setter */

  public function setKey($key) {
    $this->key = $key;
  }

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
    $onBeforeLock = $this->onBeforeLock();
    $onLock = $this->onLock();
    $onRefuseLock = $this->onRefuseLock();

    if ($onBeforeLock($this, $key)) {
      $this->setLocked();
      return $onLock($this, $key);
    }
    else
      return $onRefuseLock($this, $key);
  }

  public function unlock(Key $key) {
    $onBeforeUnlock = $this->onBeforeUnlock();
    $onUnlock = $this->onUnlock();
    $onRefuseUnlock = $this->onRefuseUnlock();

    if ($onBeforeUnlock($this, $key)) {
      $this->setUnlocked();
      return $onUnlock($this, $key);
    }
    else
      return $onRefuseUnlock($this, $key);
  }

  public function isLocked() {
    return $this->locked;
  }

  public function isUnlocked() {
    return !$this->isLocked();
  }

  /* Event Closure Registration Functions */

  public function onBeforeLock($closure=null) {
    if ($closure)
      $this->onBeforeLockClosure = $this->serializableClosure($closure);
    return $this->onBeforeLockClosure;
  }

  public function onLock($closure=null) {
    if ($closure)
      $this->onLockClosure = $this->serializableClosure($closure);
    return $this->onLockClosure;
  }

  public function onRefuseLock($closure=null) {
    if ($closure)
      $this->onRefuseLockClosure = $this->serializableClosure($closure);
    return $this->onRefuseLockClosure;
  }

  public function onBeforeUnlock($closure=null) {
    if ($closure)
      $this->onBeforeUnlockClosure = $this->serializableClosure($closure);
    return $this->onBeforeUnlockClosure;
  }

  public function onUnlock($closure=null) {
    if ($closure)
      $this->onUnlockClosure = $this->serializableClosure($closure);
    return $this->onUnlockClosure;
  }

  public function onRefuseUnlock($closure=null) {
    if ($closure)
      $this->onRefuseUnlockClosure = $this->serializableClosure($closure);
    return $this->onRefuseUnlockClosure;
  }
}
