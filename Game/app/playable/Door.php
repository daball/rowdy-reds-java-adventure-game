<?php

namespace playable;

require_once 'GameObject.php';
require_once 'IOpenable.php';
require_once 'ICloseable.php';
require_once 'ICollidable.php';
require_once 'TCreate.php';
require_once 'TOpenable.php';
require_once 'TCloseable.php';
require_once 'TCollidable.php';
require_once __DIR__.'/../util/ISerializable.php';

/**
 * A Door game item must be opened in order to pass to the next room.
 */
class Door extends GameObject implements IOpenable, ICloseable, ICollidable, \util\ISerializable, \Serializable
{
  use TOpenable;
  use TCloseable;
  use TCollidable;
  use TCreate;

  protected function __construct()
  {
    parent::__construct();

    $this->onInspect(function () {
      return "There is a door.";
    });

    $this->setExplainCollision(function ($direction)
    {
      return "There is a door blocking you from going $direction.";
    });

    $this->onOpen(function ($success) {
      if ($success) {
        return "The door swings open.";
      }
      else {
        return "The door does not open.";
      }
    });

    $this->onClose(function ($success) {
      if ($success) {
        return "The door slams shut.";
      }
      else {
        return "The door does not open.";
      }
    });

    $this->setExplainCollision(function ($direction) {
      return "There is a door blocking you from going $direction.";
    });
  }

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'description' => $this->description,
        'opened' => $this->opened,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->description = $data['description'];
    $this->opened = $data['opened'];
  }
}
