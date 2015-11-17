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

  public function __construct($name)
  {
    parent::__construct($name);
    $this->directions = new RoomDirections();
    $this->define(function ($room) {
      $inspector = new Inspector();
      $inspector->onInspect(function ($inspector) {
        return "You enter into an empty room.";
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
    return $this->getDirection($direction)->getComponent('Inspector')->inspect();
  }
}
