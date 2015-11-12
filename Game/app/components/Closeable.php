<?php

namespace components;

require_once 'BaseComponent.php';
require_once 'Openable.php';

/**
 * The Closeable
 * @author David Ball
 * @ignore
 **/
class Closeable extends BaseComponent
{
  protected $openable = null;
  protected $onCloseCallback = null;

  public function __construct(Openable $openable) {
    $this->openable = $openable;
    $this->onClose(function ($closeable) {
      if ($closeable->isClosed())
        return "You closed the object.";
      else
        return "You tried to close the object, but it didn't close.";
    });
  }

  public function getOpenable() {
    return $this->openable;
  }

  public function close() {
    $this->getOpenable()->setClosed();
    $onCloseCallback = $this->onCloseCallback;
    return $onCloseCallback($this);
  }

  public function isClosed() {
    return !$this->getOpenable()->isOpened();
  }

  public function onClose($callback) {
    $this->onCloseCallback = $callback;
  }
}
