<?php

namespace game;

require_once 'RoomDirections.php';
require_once 'GameObject.php';
require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Container.php';
require_once __DIR__.'/../util/PubSubMessageQueue.php';

use \components\Container;
use \components\Inspector;
use \engine\GameState;
use \util\PubSubMessageQueue;

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
   * Is the room dark?
   **/
  protected $dark = false;

  /**
   * When you enter the room, the last room you were in.
   * Required for navigation purposes in terms of the darkness,
   * which, while not an obstacle, modifies game engine behavior.
   **/
  protected $lastRoom = "";

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
      $room->subscribe("Player", function ($sender, $queue, $message) use ($room) {
        if (!is_array($message)) return;
        if ($message['action'] == 'setLocation' && $message['newLocation'] == $room->getName()) {
          $room->setLastRoomName($message['oldLocation']);
        }
      });
    });
  }

  public function connectToRoom($direction, $nextRoom) {
    $this->getDirection($direction)->setNextRoomName($nextRoom->getName());
    $nextRoom->getDirection(Direction::oppositeDirection($direction))->setNextRoomName($this->getName());
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
    if ($this->isDark())
      return "darkRoom.jpg";
    else
      return $this->imageUrl;
  }

  public function setImageUrl($imageUrl) {
    $this->imageUrl = $imageUrl;
  }

  public function isDark() {
    return $this->dark;
  }

  public function setDark($dark) {
    $this->dark = $dark;
    return $this->isDark();
  }

  public function getLastRoomName() {
    return $this->lastRoom;
  }

  public function setLastRoomName($lastRoom) {
    $this->lastRoom = $lastRoom;
    return $this->getLastRoomName();
  }

  public function getLastRoomDirection() {
    $directions = array(Direction::$n, Direction::$s, Direction::$e, Direction::$w, Direction::$u, Direction::$d);
    foreach ($directions as $direction) {
      if ($this->getRoomNameAtDirection($direction) == $this->getLastRoom()) {
        return $direction;
      }
    }
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
