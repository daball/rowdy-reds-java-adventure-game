<?php

namespace playable;

/**
 * Represents an IOpenable game item.
 */
interface IOpenable
{
  /**
   * @return boolean
   */
  public function isOpened();

  /**
   * Opens an Openable item.
   * @return String
   **/
  public function open();

  public function onOpen($fn);
}
