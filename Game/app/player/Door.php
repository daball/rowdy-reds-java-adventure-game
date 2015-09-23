<?php

require_once 'Openable.php';

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class Door implements Openable
{
  /**
   * @ignore
   */
  private $isOpen = false;

  /**
   * @return boolean
   */
  public function isOpen() {
    return $this->isOpen;
  }

  /**
   * Opens the door.
   * @return String
   **/
  public function open() {
    $this->isOpen = true;
    return "The door swings open.";
  }
}
