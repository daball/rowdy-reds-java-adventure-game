<?php

namespace playable;

trait TOpenable
{
  /**
   * @ignore
   */
  private $opened = false;
  private $openCallback = null;

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
    if (!$this->opened)
      $this->opened = true;
    if ($this->openCallback)
     return $this->openCallback($this->opened);
    else
     return "This item has already been opened.";
  }

  public function onOpen($fn) {
    $this->openCallback = $fn;
    return $this;
  }
}
