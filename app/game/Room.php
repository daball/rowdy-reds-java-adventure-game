<?php

namespace game;

require_once 'RoomDirections.php';
require_once 'GameObject.php';
require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Container.php';

use \components\Container;
use \components\Inspector;
use \engine\GameState;

/**
 * Defines a Room in the Game
 **/
class Room extends GameObject
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

  // Should be a constructor that also requires a description, but PHP does not allow overloading.
  // I didn't want to break all the game maps (major PITA factor), so I left this constructor in
  public function __construct($name)
  {
    parent::__construct($name);
    $this->directions = new RoomDirections();
    $this->define(function ($room) {
      $container = new Container();
      $room->addComponent($container);
    });
  }

  public static function constructBasicRoom($name, $desc, $imageUrl) {
    $room = new Room($name);
    $room -> setDescription($desc);
    $room -> setImageUrl($imageUrl);
    return $room;
  }
  
  public function setDescription($description)
  {
    $inspector = new Inspector();
    $inspector->onInspect(function () use ($description) {
      return $description;
    });
    $this->addComponent($inspector);
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

  public function getRoomNameAtDirection($direction) {
    return $this->directions->getDirection($direction)->getNextRoomName();
  }

  public function getRoomAtDirection($direction) {
    return GameState::getInstance()->getGame()->getRoom($this->getRoomNameAtDirection($direction));
  }

  public function inspectRoom() {
    return $this->getComponent('Inspector')->inspect();
  }

  public function inspectDirection($direction) {
    if (($item = $this->getColliderItemInDirection($direction)) != null
      && $item->getComponent('Collider')->isEnabled())
      return $item->getComponent('Inspector')->inspect();
    else
      return $this->getDirection($direction)->getComponent('Inspector')->inspect();
  }

  /**
   * @ignore
   **/
  public function getColliderItemInDirection($direction) {
    foreach ($this->getComponent('Container')->getAllItems() as $item) {
      if ($item->hasComponent('Collider')
        && Direction::fullDirection($item->getComponent('Collider')->getDirection()) == Direction::fullDirection($direction))
        return $item;
    }
  }
}
