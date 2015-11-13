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
  protected $onInspectCallback = null;

  /**
   * @ignore
   */
  public function __construct() {
    $this->onInspect(function ($inspector) {
      return "You don't see anything interesting about this object.";
    });
  }

  /**
   * @ignore
   */
  public function inspect() {
    $description = "";
    $onInspectCallback = $this->onInspectCallback;
    return $onInspectCallback($this);
  }

  /**
   * @ignore
   */
  public function onInspect($callback) {
    $this->onInspectCallback = $callback;
  }
}
