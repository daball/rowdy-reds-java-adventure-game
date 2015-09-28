<?php

namespace playable;

trait TInspectable
{
  public $description = "This is an inspectable item.";
  private $inspectCallback = null;

  /**
   * Inspects the contents of the room.
   **/
  public function inspect()
  {
    $cb = $this->inspectCallback;
    if ($this->inspectCallback)
    {
      return $cb();
    }
    else
      return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  public function onInspect($fn) {

    $this->inspectCallback = $fn;
    return $this;
  }
}
