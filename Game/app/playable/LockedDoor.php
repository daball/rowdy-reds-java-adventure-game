<?php

namespace playable;

require_once "Door.php";
require_once "ILockable.php";
require_once "IUnlockable.php";
require_once "Key.php";
require_once "TUnlockable.php";
require_once "TCloseable.php";
require_once "TCollidable.php";
require_once "TCreateWithKey.php";

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class LockedDoor extends Door implements /*ILockable,*/ IUnlockable
{
  use TUnlockable;
  use TCloseable;
  use TCollidable;
  use TCreateWithKey;

  protected function __construct($key) {
    parent::__construct();
    $this->key = $key;
    $this->onOpen(function () {
      if ($this->unlocked) {
        if (!$this->opened) {
          $this->opened = true;
          return "The door swings open.";
        }
        else {
          return "This door has already been opened.";
        }
      }
      else {
        if ($this->opened)
          return "This door has already been opened.";
        else
          return "You try to open the door, but this door is locked.";
      }
    });
    /*$this->onLock(function ($success) {
      if ($this->unlocked) {
        if ($success)
          return "The key turns and the door is locked. The door remains opened.";
        else
          return "The key turns and the door is locked. The door remains closed.";
      }
      else {
        return "This door has already been locked.";
      }
    });*/
    $this->onUnlock(function ($success) {
      if ($success)
        return "You have unlocked the door. The door swings open.";
      else
        return "You must use a key to unlock a locked door.";
    });
  }
}
