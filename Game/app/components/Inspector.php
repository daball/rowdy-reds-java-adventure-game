<?php

namespace components;

require_once 'BaseComponent.php';

/**
 * The Inspector component allows any GameObject to be inspected.
 * @author David Ball
 **/
class Inspector extends BaseComponent
{
  protected $description = "The Inspector component allows any GameObject to be inspected.";
  protected $beforeInspectCallback = null;

  public function setDescription($description) {
    $this->description = $description;
    return $this->getDescription();
  }

  public function getDescription() {
    return $this->description;
  }

  public function inspect() {
    $description = "";
    $cb = $this->beforeInspectCallback;
    if ($cb)
      $description = $cb($this);
    if (!$description)
      $description = $this->getDescription();
    return $description;
  }

  public function onBeforeInspect($callback) {
    $this->beforeInspectCallback = $callback;
  }
}
