<?php

/**
 * Represents an ILockable game item.
 */
interface ILockable
{
  /**
   * @return boolean
   */
  public function isLocked();

  /**
   * Locks an ILockable item.
   * @return String
   **/
  public function lock($key);
}
