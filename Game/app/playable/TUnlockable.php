<?php

namespace playable;

require_once 'GameObject.php';
require_once 'IOpenable.php';
require_once 'ICloseable.php';
require_once 'ICollidable.php';


/**
 * Note to implementers: If using TUnlockable, don't use TOpenable, but do use IOpenable.
 **/
trait TUnlockable
{
  /**
   * @ignore
   */
  private $opened = false;
  private $unlocked = false;
  private $key = null;
  private $onUnlock = null;
  private $openCallback = null;
  private $unlockCallback = null;

  /* IOpenable implementation */
  /**
   * @return boolean
   * @ignore
   */
  public function isOpened() {
    return $this->opened;
  }

  /**
   * Opens the door.
   * @return String
   **/
  public function open() {
    $cb = $this->openCallback;
    if ($this->unlocked) {
      if (!$this->opened) {
        $this->opened = true;
        if ($this->openCallback)
          return $cb($this->opened);
         else
          return "The item opens.";
       }
       else {
         if ($this->openCallback)
          return $cb($this->opened);
         else
          return "This item has already been opened.";
       }
     }
     else {
       if ($this->opened) {
         if ($this->openCallback)
          return $cb($this->opened);
         else
          return "This item has already been opened.";
        }
       else {
         if ($this->openCallback)
          return $cb($this->opened);
         else
          return "You try to open the item, but the item is locked.";
       }
     }
   }

  public function onOpen($fn) {
    $this->openCallback = $fn;
    return $this;
  }

  /**
   * Unlocks an Unlockable item.
   * @return String
   **/
  public function unlock(Key $key)
  {
    $cb = $this->unlockCallback;
    if (is_a($key, "\playable\Key")) {
      if ($this->key->getKey() === $key->getKey()) {
        $this->unlocked = true;
        $this->opened = true;
        if ($cb)
          return $cb($this->unlocked);
        else
          return "The item was unlocked.";
      }
    }
    else {
      if ($cb !== null)
        return $cb($this->unlocked);
      else
        return "You must use a key to unlock the foot locker.";
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
    $this->unlockCallback = $fn;
  }
}
