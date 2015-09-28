<?php

namespace playable;

require_once "GameObject.php";
require_once "IContainer.php";
require_once "TCreate.php";
require_once "TContainer.php";
require_once "TOpenable.php";
require_once "TCloseable.php";
require_once __DIR__.'/../util/ISerializable.php';

class Container extends GameObject implements IContainer, \util\ISerializable, \Serializable
{
  use TCreate;
  use TContainer;
  use TOpenable;
  use TCloseable;

  protected function __construct() {
    parent::__construct();
    $this->onOpen(function ($success) {
      if ($success) {
        $this->opened = true;
        return "The container swings open.";
      }
      else {
        return "The container does not open.";
      }
    });
    $this->close(function ($success) {
      if ($success)
        return "You have closed the container.";
      else
        return "The container does not close.";
    });
  }

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'items' => $this->items,
        'opened' => $this->opened,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->__construct();
    $this->description = $data['description'];
    $this->items = $data['items'];
    $this->opened = $data['opened'];
  }
}
