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
  protected $description = "The Inspector component allows any GameObject to be inspected.";
  /**
   * @ignore
   */
  protected $beforeInspectCallback = null;

  /**
   * @ignore
   */
  public function __construct() {
    $this->onBeforeInspect(function ($inspector) {
      return $inspector->getDescription();
    });
  }

  /**
   * @ignore
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this->getDescription();
  }

  /**
   * @ignore
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * @ignore
   */
  public function inspect() {
    $description = "";
    $cb = $this->beforeInspectCallback;
    if ($cb)
      $description = $cb($this);
    if (!$description)
      $description = $this->getDescription();
    return $description;
  }

  /**
   * @ignore
   */
  public function onBeforeInspect($callback) {
    $this->beforeInspectCallback = $callback;
  }
}
