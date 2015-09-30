<?php

namespace playable;

require_once 'GameObject.php';
require_once 'IOpenable.php';
require_once 'ICloseable.php';
require_once 'ICollidable.php';


trait TAssignable
{
  /**
   * @ignore
   */
  private $assignCallback = null;

  /**
   * @return boolean
   */
  public function assign($itemName, &$fromTarget, &$toTarget)
  {
    $cb = $this->assignCallback;
    $toTarget->items->addItem($itemName, $this);
    $fromTarget->items->removeItem($itemName);
    if ($cb)
      return $cb();
    return true;
  }

  public function onUnlock($fn)
  {
    $this->unlockCallback = $fn;
  }
}
