<?php

namespace components;

require_once 'BaseComponent.php';
require_once 'Lockable.php';
require_once __DIR__.'/../playable/Key.php';

use \playable\Key;

/**
 * The Unlockable
 * @author David Ball
 * @ignore
 **/
class Unlockable extends BaseComponent
{
  protected $lockable = null;
  protected $onUnlockCallback = null;

  public function __construct(Lockable $lockable) {
    $this->lockable = $lockable;
    $this->onUnlock(function ($unlockable) {
      if ($unlockable->isUnlocked()) {
        return "You turned the key and the object unlocks.";
      }
      else
        return "You insert the key, but it doesn't fit the lock.";
    });
  }

  public function getLockable() {
    return $this->lockable;
  }

  public function unlock(Key $key) {
    if ($this->getLockable()->getKey()->getKey() == $key->getKey())
      $this->getLockable()->setUnlocked();
    if ($this->getParent() && $this->getParent()->hasComponent('Openable'))
      $this->getParent()->getComponent('Openable')->setOpened();
    $onUnlockCallback = $this->onUnlockCallback;
    return $onUnlockCallback($this);
  }

  public function isUnlocked() {
    return !$this->getLockable()->isLocked();
  }

  public function onUnlock($callback) {
    $this->onUnlockCallback = $callback;
  }
}
