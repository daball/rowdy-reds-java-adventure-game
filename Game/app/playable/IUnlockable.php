<?php

/**
 * Represents an IUnlockable game item.
 */
interface IUnlockable
{
  /**
   * @return boolean
   */
  public function isUnlocked();

  /**
   * Unlocks an IUnlockable item.
   * @return String
   **/
  public function unlock($key);
}
