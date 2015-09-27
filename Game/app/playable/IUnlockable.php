<?php

namespace playable;

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

  /**
   * Set a callback for the successful unlock() call.
   * Callback should be in the format function($success: boolean)
   **/
  public function onUnlock($fn);
}
