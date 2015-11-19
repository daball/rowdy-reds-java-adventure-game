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

  protected $onBeforeOpenClosure = null;
  protected $onOpenClosure = null;
  protected $onRefuseOpenClosure = null;

  protected $onBeforeCloseClosure = null;
  protected $onCloseClosure = null;
  protected $onRefuseCloseClosure = null;

  public function __construct() {
    $this->define(function ($openable) {
      $openable->onBeforeOpen(function ($openable) {
        //No reason not to
        return true;
      });
      $openable->onBeforeClose(function ($openable) {
        //No reason not to
        return true;
      });
      $openable->onOpen(function ($openable) {
        return "The object was opened.";
      });
      $openable->onRefuseOpen(function ($openable) {
        return "The object was not opened.";
      });
      $openable->onClose(function ($openable) {
        return "You closed the object.";
      });
      $openable->onRefuseClose(function ($openable) {
        return "You tried to close the object, but it didn't close.";
      });
    });
  }

  /* Property Getter/Setter */

  public function setOpened() {
    $this->opened = true;
  }

  public function setClosed() {
    $this->opened = false;
  }

  /* Public API for Component */

  public function open() {
    $onBeforeOpen = $this->onBeforeOpen();
    $onOpen = $this->onOpen();
    $onRefuseOpen = $this->onRefuseOpen();

    if ($onBeforeOpen($this)) {
      $this->setOpened();
      return $onOpen($this);
    }
    else
      return $onRefuseOpen($this);
  }

  public function close() {
    $onBeforeClose = $this->onBeforeClose;
    $onClose = $this->onClose;
    $onRefuseClose = $this->onRefuseClose;

    if ($onBeforeClose($this)) {
      $this->setClosed();
      return $onClose($this);
    }
    else
      return $onRefuseClose($this);
  }

  public function isOpened() {
    return $this->opened;
  }

  public function isClosed() {
    return !$this->isOpened();
  }

  /* Event Closure Registration Functions */

  public function onBeforeOpen($closure=null) {
    if ($closure)
      $this->onBeforeOpenClosure = $this->serializableClosure($closure);
    return $this->onBeforeOpenClosure;
  }

  public function onOpen($closure=null) {
    if ($closure)
      $this->onOpenClosure = $this->serializableClosure($closure);
    return $this->onOpenClosure;
  }

  public function onRefuseOpen($closure=null) {
    if ($closure)
      $this->onRefuseOpenClosure = $this->serializableClosure($closure);
    return $this->onRefuseOpenClosure;
  }

  public function onBeforeClose($closure=null) {
    if ($closure)
      $this->onBeforeCloseClosure = $this->serializableClosure($closure);
    return $this->onBeforeCloseClosure;
  }

  public function onClose($closure=null) {
    if ($closure)
      $this->onCloseClosure = $this->serializableClosure($closure);
    return $this->onCloseClosure;
  }

  public function onRefuseClose($closure=null) {
    if ($closure)
      $this->onRefuseCloseClosure = $this->serializableClosure($closure);
    return $this->onRefuseCloseClosure;
  }
}
