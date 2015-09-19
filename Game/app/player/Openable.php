<?php

/**
 * Represents an Openable game item.
 */
interface Openable
{
  /**
   * @return boolean
   */
  public function isOpen();

  /**
   * Opens an Openable item.
   * @return String
   **/
  public function open();
}
