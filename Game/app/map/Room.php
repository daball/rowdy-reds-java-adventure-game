<?php

namespace map;

require_once 'RoomDirections.php';
require_once __DIR__.'/../playable/GameObject.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Container.php';

use \components\Container;
use \components\Inspector;
use \playable\GameObject;

/**
 * Defines a Room in the Game
 **/
class Room extends GameObject //implements \Serializable
{
  /**
   * Image path (URL)
   * @type String
   * @ignore
   **/
  protected $imageUrl = "";

  /**
   * Is this the spawn point?
   * @type bool
   * @ignore
   **/
  protected $spawn = false;

  /**
   * Contains data for each of the cardinal directions for the room.
   **/
  protected $directions;

  public function __construct($name)
  {
    parent::__construct($name);
    $this->directions = new RoomDirections();
    $this->define(function ($room) {
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
      $room->addComponent($inspector);
    });
    $this->define(function ($room) {
      $container = new Container();
      $room->addComponent($container);
    });
  }

  public function setSpawnPoint() {
    $this->spawn = true;
  }

  public function unsetSpawnPoint() {
    $this->spawn = false;
  }

  public function isSpawnPoint() {
    return $this->spawn;
  }

  public function getImageUrl() {
    return $this->imageUrl;
  }

  public function setImageUrl($imageUrl) {
    $this->imageUrl = $imageUrl;
  }

  public function getDirection($direction) {
    return $this->directions->getDirection($direction);
  }

  // /* ISerializable interface implementation */
  // public function serialize() {
  //   return serialize(
  //     array(
  //       'name' => $this->name,
  //       'description' => $this->description,
  //       'imageUrl' => $this->imageUrl,
  //       'spawn' => $this->spawn,
  //       'directions' => $this->directions,
  //     )
  //   );
  // }
  //
  // public function unserialize($data) {
  //   $data = unserialize($data);
  //   $this->__construct();
  //   $this->name = $data['name'];
  //   $this->description = $data['description'];
  //   $this->imageUrl = $data['imageUrl'];
  //   $this->spawn = $data['spawn'];
  //   $this->directions = $data['directions'];
  //   $this->items = $data['items'];
  // }
}
