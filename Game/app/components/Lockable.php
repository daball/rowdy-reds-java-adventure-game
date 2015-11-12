<?php

namespace components;

require_once 'BaseComponent.php';

/**
 * The Inspector component allows any GameObject to be inspected.
 * @author David Ball
 **/
class Lockable extends BaseComponent
{
  protected $locked = true;

  public function unlock() {
    $this->locked = false;
  }

  public function isLocked() {
    return $this->locked;
  }
}
