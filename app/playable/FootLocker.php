<?php

namespace playable;

require_once 'LockableContainer.php';

/**
 * A foot locker game item must be opened in order to pass to the next room.
 */
class FootLocker extends LockableContainer
{
  public function __construct($name, $secret) {
    parent::__construct($name, $secret);
    $this->define(function ($footLocker) {
      $openable = $this->getComponent("Openable");
      $openable->onOpen(function () {
        if ($this->unlocked) {
          if (!$this->opened) {
            $this->opened = true;
            return "The foot locker swings open.";
          }
          else {
            return "This foot locker has already been opened.";
          }
        }
        else {
          if ($this->opened)
            return "This foot locker has already been opened.";
          else
            return "You try to open the foot locker, but this foot locker is locked.";
        }
      });
      $lockable = new Lockable($secret);
      $lockable->onLock(function ($success) {
        if ($this->unlocked) {
          if ($success)
            return "The key turns and the foot locker is locked. The foot locker remains opened.";
          else
            return "The key turns and the foot locker is locked. The foot locker remains closed.";
        }
        else {
          return "This foot locker has already been locked.";
        }
      });
      $lockable->onUnlock(function ($success) {
        if ($success)
          return "You have unlocked the foot locker. The foot locker swings open.";
        else
          return "You must use a key to unlock a locked foot locker.";
      });
    });
  }
}
