<?php

namespace playable;

require_once 'IOpenable.php';
require_once 'ICloseable.php';
require_once 'ICollidable.php';

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class FootLocker implements IOpenable, ICloseable, IUnlockable, ICollidable //, IVandalisable
{
  /**
   * @ignore
   */
  private $opened = false;
  private $locked = false;
  private $callback = null;

  /* IOpenable interface implementation */

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
    if (!$this->opened) {
      $this->opened = true;
      return "The door swings open.";
    }
    else {
      return "This door has already been opened.";
    }
  }

  /* ICloseable interface implementation */

  /**
   * @return boolean
   * @ignore
   */
  public function isClosed() {
    return !$this->opened;
  }

  /**
   * Closes the door.
   * @return String
   **/
  public function close() {
    if ($this->opened) {
      $this->opened = false;
      return "The door slams shut.";
    }
    else {
      return "This door has already been closed.";
    }
  }

  /* ICollidable interface implementation. */

  /**
   * Returns a boolean indicating if the Player can proceed past the obstacle.
   * @return boolean
   * @ignore
   **/
  public function isInTheWay() {
    return $this->isClosed();
  }

  /**
   * @ignore
   **/
  public function explainCollision($direction) {
    return "There is a door blocking you from going $direction.";
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
          return $this->fn(true);
        else
          return "You have unlocked the foot locker.";
      }
    }
    else {
      if ($this->fn !== null)
        return $this->fn(true);
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
    $this->callback = $fn;
  }

}
