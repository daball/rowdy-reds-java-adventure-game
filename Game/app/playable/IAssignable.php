<?php

namespace playable;

interface IAssignable {
  /**
   * Assigns an IAssignable item to a target.
   * @return String
   **/
  public function assign($itemName, &$fromTarget, &$toTarget);

  public function onAssign($fn);
}
