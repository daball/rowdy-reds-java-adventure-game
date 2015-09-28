<?php

namespace playable;

/**
 * Note to implementers: Before using TOpenable, first use TCloseable or otherwise
 * define $opened.
 **/
trait TCloseable
{
  private $closeCallback = null;

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
    $cb = $this->closeCallback;
    if ($this->opened)
      $this->opened = false;
    if ($cb)
      return $cb(!$this->opened);
    else {
      if (!$this->opened)
        return "The item was closed.";
      else
        return "The item was not closed.";
    }
  }

  public function onClose($fn) {
    $this->closeCallback = $fn;
    return $this;
  }
}
