<?php

namespace playable;

require_once "IInspectable.php";
require_once "TInspectable.php";
require_once __DIR__.'/../util/ISerializable.php';

abstract class GameObject implements IInspectable {
  use TInspectable;

  protected function __construct()
  {
    $this->onInspect(function () {
      return "This is a GameObject, but you should describe it first. That means you, developer.";
    });
  }
}
