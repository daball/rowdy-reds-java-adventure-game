<?php

/**
 * Represents an ICloseable game item.
 */
interface ICloseable
{
  /**
   * @return boolean
   */
  public function isClosed();

  /**
   * Closes an ICloseable item.
   * @return String
   **/
  public function close();
}
