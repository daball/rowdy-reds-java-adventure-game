<?php

namespace playable;

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

  public function onAssign($fn)
  {
    $this->assignCallback = $fn;
  }
}
