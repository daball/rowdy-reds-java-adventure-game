<?php

namespace playable;

require_once 'IOpenable.php';
require_once 'ICloseable.php';
require_once 'ICollidable.php';

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class Door implements IOpenable, ICloseable, ICollidable
{
  /**
   * @ignore
   */
  private $opened = false;

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

  public function explainCollision() {
    return "There is a door blocking you from going $d.";
  }

}
