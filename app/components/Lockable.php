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
    $this->define(function ($lockable) use ($key) {
      //override openable logic to cater to lockable
      if ($lockable->getParent()
       && $lockable->getParent()->hasComponent('Openable'))
      {
        $openable = $lockable->getParent()->getComponent('Openable');
        $onBeforeOpen = $openable->onBeforeOpen();
        $openable->onBeforeOpen(function ($openable) use ($onBeforeOpen) {
          return $onBeforeOpen($openable)
              && !$openable->getParent()->getComponent('Lockable')->isLocked();
        });
        $onRefuseOpen = $openable->onRefuseOpen();
        $openable->onRefuseOpen(function ($openable) use ($onRefuseOpen) {
          return $onRefuseOpen() . " Perhaps it is locked.";
        });
      }

      $lockable->setKey($key);
      //This defines the logic for locking/unlocking an object
      $lockLogic = function ($lockable, $keyProvided) {
        return ($lockable->getKey()->getSecret() == $keyProvided->getSecret());
      };
      $lockable->onBeforeLock($lockLogic);
      $lockable->onBeforeUnlock($lockLogic);

      $lockable->onLock(function ($lockable, $keyProvided) {
        return "You turned the key and the object locks.";
      });
      $lockable->onRefuseLock(function ($lockable, $keyProvided) {
        return "You insert the key, but it doesn't fit the lock.";
      });
      $lockable->onUnlock(function ($lockable, $keyProvided) {
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

  /* Event Callback Registration Functions */

  public function onBeforeLock($callback=null) {
    if ($callback)
      $this->onBeforeLockCallback = $this->serializableClosure($callback);
    return $this->onBeforeLockCallback;
  }

  public function onLock($callback=null) {
    if ($callback)
      $this->onLockCallback = $this->serializableClosure($callback);
    return $this->onLockCallback;
  }

  public function onRefuseLock($callback=null) {
    if ($callback)
      $this->onRefuseLockCallback = $this->serializableClosure($callback);
    return $this->onRefuseLockCallback;
  }

  public function onBeforeUnlock($callback=null) {
    if ($callback)
      $this->onBeforeUnlockCallback = $this->serializableClosure($callback);
    return $this->onBeforeUnlockCallback;
  }

  public function onUnlock($callback=null) {
    if ($callback)
      $this->onUnlockCallback = $this->serializableClosure($callback);
    return $this->onUnlockCallback;
  }

  public function onRefuseUnlock($callback=null) {
    if ($callback)
      $this->onRefuseUnlockCallback = $this->serializableClosure($callback);
    return $this->onRefuseUnlockCallback;
  }
}
