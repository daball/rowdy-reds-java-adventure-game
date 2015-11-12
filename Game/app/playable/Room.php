<?php

namespace playable;

use \map\RoomDirections;

require_once __DIR__.'/../map/RoomDirections.php';
require_once __DIR__.'/../playable/IInspectable.php';
require_once __DIR__.'/../playable/IContainer.php';
require_once __DIR__.'/../playable/TInspectable.php';

/**
 * Defines a Room in the Game
 **/
class Room extends GameObject implements \Serializable
{
  /**
   * Name of the room (used internally as a reference)
   * @type String
   * @ignore
   **/
  public $name = "";

  /**
   * Image path (URL)
   * @type String
   * @ignore
   **/
  public $imageUrl = "";

  /**
   * Is this the spawn point?
   * @type bool
   * @ignore
   **/
  public $spawn = false;

  /**
   * Contains data for each of the cardinal directions for the room.
   **/
  public $directions;

  public function __construct()
  {
    $this->directions = new RoomDirections();
    $inspector = new Inspector();
    $inspector->onInspect(function ($inspector) {
      $obviousExits = [];
      if ($this->directions->n->nextRoom && $this->directions->n->obvious) array_push($obviousExits, "NORTH");
      if ($this->directions->e->nextRoom && $this->directions->e->obvious) array_push($obviousExits, "EAST");
      if ($this->directions->s->nextRoom && $this->directions->s->obvious) array_push($obviousExits, "SOUTH");
      if ($this->directions->w->nextRoom && $this->directions->w->obvious) array_push($obviousExits, "WEST");
      $obviousExits = implode(', ', $obviousExits);
      return "\n$this->description\nThe obvious exits are: $obviousExits";
    });
    $this->addComponent($inspector);
  }

  /* ISerializable interface implementation */

  public function serialize() {
    return serialize(
      array(
        'name' => $this->name,
        'description' => $this->description,
        'imageUrl' => $this->imageUrl,
        'spawn' => $this->spawn,
        'directions' => $this->directions,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->__construct();
    $this->name = $data['name'];
    $this->description = $data['description'];
    $this->imageUrl = $data['imageUrl'];
    $this->spawn = $data['spawn'];
    $this->directions = $data['directions'];
    $this->items = $data['items'];
  }
}
