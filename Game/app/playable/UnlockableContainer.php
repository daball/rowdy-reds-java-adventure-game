<?php

namespace playable;

require_once "Container.php";
require_once "IUnlockable.php";
require_once "TUnlockable.php";
require_once "TCloseable.php";
require_once "TContainer.php";
require_once "TCreateWithKey.php";

class UnlockableContainer extends Container implements IUnlockable
{
  use TUnlockable;
  use TCloseable;
  use TCreateWithKey;

  protected function __construct($key) {
    parent::__construct();
    $this->key = $key;
    $this->onOpen(function () {
      if ($this->unlocked) {
        if (!$this->opened) {
          $this->opened = true;
          return "The container swings open.";
        }
        else {
          return "This container has already been opened.";
        }
      }
      else {
        if ($this->opened)
          return "This container has already been opened.";
        else
          return "You try to open the container, but this container is locked.";
      }
    });
    /*$this->onLock(function ($success) {
      if ($this->unlocked) {
        if ($success)
          return "The key turns and the container is locked. The container remains opened.";
        else
          return "The key turns and the container is locked. The container remains closed.";
      }
      else {
        return "This container has already been locked.";
      }
    });*/
    $this->onUnlock(function ($success) {
      if ($success)
        return "You have unlocked the container. The container swings open.";
      else
        return "You must use a key to unlock a locked container.";
    });
  }
}
