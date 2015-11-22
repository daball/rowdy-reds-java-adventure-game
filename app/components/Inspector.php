<?php

namespace components;

require_once 'BaseComponent.php';

/**
 * The Inspector component allows any GameObject to be inspected.
 * @author David Ball
 * @ignore
 **/
class Inspector extends BaseComponent
{
  /**
   * @ignore
   */
  public function __construct() {
    $this->define(function ($inspector) {
      $inspector->onInspect(function ($inspector) {
        return "You don't see anything interesting about this object.";
      });
    });
  }

  /**
   * @ignore
   */
  public function inspect() {
    return $this->trigger('inspect', array($this));
  }

  /**
   * @ignore
   */
  public function onInspect($closure=null) {
    return $this->on("inspect", $closure);
  }
}
