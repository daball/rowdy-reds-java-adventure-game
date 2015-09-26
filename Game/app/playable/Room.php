<?php

namespace playable;

use \map\RoomDirections;

require_once __DIR__.'/../map/RoomDirections.php';
require_once __DIR__.'/../util/ISerializable.php';
require_once __DIR__.'/../playable/IInspectable.php';

/**
 * Defines a Room in the Game
 **/
class Room implements \playable\IInspectable, \util\ISerializable
{
  /**
   * Name of the room (used internally as a reference)
   * @type String
   * @ignore
   **/
  public $name = "";

  /**
   * Description of the room
   * @type String
   * @ignore
   **/
  public $description = "";

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

  /**
   * Array of items within the room.
   **/
  public $items = array();

  public function __construct()
  {
    $this->directions = new RoomDirections();
  }

  /**
   * Inspects the contents of the room.
   **/
  public function inspect()
  {
    $obviousExits = [];
    if ($this->directions->n && $this->directions->n->obvious) array_push($obviousExits, "NORTH");
    if ($this->directions->e && $this->directions->e->obvious) array_push($obviousExits, "EAST");
    if ($this->directions->s && $this->directions->s->obvious) array_push($obviousExits, "SOUTH");
    if ($this->directions->w && $this->directions->w->obvious) array_push($obviousExits, "WEST");
    $obviousExits = implode(', ', $obviousExits);
    return $this->description . "\n\nThe obvious exits are: $obviousExits";
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
        'items' => $this->items,
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->name = $data['name'];
    $this->description = $data['description'];
    $this->imageUrl = $data['imageUrl'];
    $this->spawn = $data['spawn'];
    $this->directions = $data['directions'];
    $this->items = $data['items'];
  }
}
