<?php

namespace playable;

require_once "Door.php";
require_once "ILockable.php";
require_once "IUnlockable.php";
require_once "Key.php";

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class LockedDoor extends Door implements ILockable, IUnlockable
{
  /**
   * @ignore
   */
  private $unlocked = false;
  private $key = null;
  private $callback = null;

  public function __construct($key) {
    $this->key = $key;
  }

  /* Door overrides */

  /**
   * Opens the door.
   * @return String
   **/
  public function open() {
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
  }

  /* ILockable interface implementation */

  /**
   * @return boolean
   */
  public function isLocked()
  {
    return !$this->unlocked;
  }

  /**
   * Locks the LockedDoor.
   * @return String
   **/

  public function lock($key) {
    if ($this->unlocked) {
      $this->unlocked = false;
      if ($this->opened)
        return "The key turns and the door is locked. The door remains opened.";
      else
        return "The key turns and the door is locked. The door remains closed.";
    }
    else {
      return "This door has already been locked.";
    }
  }

  /* IUnlockable interface implementation */

  /**
   * Unlocks an Unlockable item.
   * @return String
   **/
  public function unlock($key)
  {
    if (is_a($key, "\playable\Key")) {
      if ($this->key->getKeyID() == $key->getKeyID()) {
        if ($this->fn !== null)
          $this->fn(true);
        else
          return "You have unlocked the door. The door swings open.";
      }
    }
    else {
      if ($this->fn !== null)
        return $this->fn(true);
      else
        return "You must use a key to unlock a locked door.";
    }
  }

  /**
   * @return boolean
   */
  public function isUnlocked()
  {
    return $this->unlocked;
  }

  public function onUnlock($fn)
  {
    $this->callback = $fn;
  }
}
