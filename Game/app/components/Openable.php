<?php

namespace components;

require_once 'BaseComponent.php';

/**
 * The Openable
 * @author David Ball
 * @ignore
 **/
class Openable extends BaseComponent
{
  protected $opened = false;

  protected $onBeforeOpenCallback = null;
  protected $onOpenCallback = null;
  protected $onRefuseOpenCallback = null;

  protected $onBeforeCloseCallback = null;
  protected $onCloseCallback = null;
  protected $onRefuseCloseCallback = null;

  public function __construct() {
    $this->onBeforeOpen(function ($openable) {
      return (
          //If Openable doesn't have a parent
          !$openable->getParent() ||
          //Or, Openable's parent doesn't have a Lockable
          !$openable->getParent()->hasComponent('Lockable') ||
          //Or, Openable's parent has a Lockable and the Lockable is unlocked
          !$openable->getParent()->getComponent('Lockable')->isLocked()
        );
    });
    $this->onBeforeClose(function ($openable) {
      //No reason not to
      return true;
    });
    $this->onOpen(function ($openable) {
      return "The object was opened.";
    });
    $this->onRefuseOpen(function ($openable) {
      return "The object was not opened. Perhaps it is locked.";
    });
    $this->onClose(function ($openable) {
      return "You closed the object.";
    });
    $this->onRefuseClose(function ($openable) {
      return "You tried to close the object, but it didn't close.";
    });
  }

  /* Property Getter/Setter */

  public function setOpen() {
    $this->opened = true;
  }

  public function setClose() {
    $this->opened = false;
  }

  /* Public API for Component */

  public function open() {
    $onBeforeOpenCallback = $this->onBeforeOpenCallback;
    $onOpenCallback = $this->onOpenCallback;
    $onRefuseOpenCallback = $this->onRefuseOpenCallback;

    if ($onBeforeOpenCallback($this)) {
      $this->setOpen();
      return $onOpenCallback($this);
    }
    else
      return $onRefuseOpenCallback($this);
  }

  public function close() {
    $onBeforeCloseCallback = $this->onBeforeCloseCallback;
    $onCloseCallback = $this->onCloseCallback;
    $onRefuseCloseCallback = $this->onRefuseCloseCallback;

    if ($onBeforeCloseCallback($this)) {
      $this->setClose();
      return $onCloseCallback($this);
    }
    else
      return $onRefuseCloseCallback($this);
  }

  public function isOpened() {
    return $this->opened;
  }

  public function isClosed() {
    return !$this->isOpened();
  }

  /* Event Callback Registration Functions */

  public function onBeforeOpen($callback) {
    $this->onBeforeOpenCallback = $callback;
  }

  public function onOpen($callback) {
    $this->onOpenCallback = $callback;
  }

  public function onRefuseOpen($callback) {
    $this->onRefuseOpenCallback = $callback;
  }

  public function onBeforeClose($callback) {
    $this->onBeforeCloseCallback = $callback;
  }

  public function onClose($callback) {
    $this->onCloseCallback = $callback;
  }

  public function onRefuseClose($callback) {
    $this->onRefuseCloseCallback = $callback;
  }
}
