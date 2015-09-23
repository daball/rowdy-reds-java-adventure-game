<?php

/**
 * Represents an Unlockable game item.
 */
interface Unlockable
{
  /**
   * @return boolean
   */
  public function isLocked();

  /**
   * Unlocks an Unlockable item.
   * @return String
   **/
  public function unlock($key);
}
