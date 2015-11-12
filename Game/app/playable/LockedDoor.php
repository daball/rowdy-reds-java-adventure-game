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
class LockedDoor extends Door implements /*ILockable,*/ IUnlockable, \Serializable
{
  use TUnlockable;
  use TCloseable;
  use TCollidable;
  use TCreateWithKey;

  protected function __construct($key) {
    parent::__construct();
    $this->key = $key;
    $this->onOpen(function ($success) {
      if ($this->unlocked) {
        if ($success) {
          $this->opened = true;
          return "The door swings open.";
        }
        else {
          return "This door has already been opened.";
        }
      }
      else {
        if ($success)
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

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'opened' => $this->opened,
        'unlocked' => $this->unlocked,
        'key' => $this->key,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->key = $data['key'];
    $this->__construct($this->key);
    $this->description = $data['description'];
    $this->opened = $data['opened'];
    $this->unlocked = $data['unlocked'];
  }
}
