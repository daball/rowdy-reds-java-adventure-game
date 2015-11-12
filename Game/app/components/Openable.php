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

  public function __construct() {
    $this->onBeforeOpen(function ($openable) {
      //check for a Lockable first
      if (
          //If Openable doesn't have a parent
          !$openable->getParent() ||
          //Or, Openable's parent doesn't have a Lockable
          !$openable->getParent()->hasComponent('Lockable') ||
          //Or, Openable's parent has a Lockable and the Lockable is unlocked
          !$openable->getParent()->getComponent('Lockable')->isLocked()
          )
            //Then Open up
            $openable->setOpened();
    });
    $this->onOpen(function ($openable) {
      if ($openable->opened) {
        return "The object was opened.";
      }
      else {
        return "The object was not opened. Perhaps it is locked.";
      }
    });
  }

  public function open() {
    $onBeforeOpenCallback = $this->onBeforeOpenCallback;
    $onOpenCallback = $this->onOpenCallback;
    if ($onBeforeOpenCallback)
      $onBeforeOpenCallback($this);
    return $onOpenCallback($this);
  }

  public function setOpened() {
    $this->opened = true;
  }

  public function setClosed() {
    $this->opened = false;
  }

  public function isOpened() {
    return $this->opened;
  }

  public function onBeforeOpen($callback) {
    $this->onBeforeOpenCallback = $callback;
  }

  public function onOpen($callback) {
    $this->onOpenCallback = $callback;
  }
}
