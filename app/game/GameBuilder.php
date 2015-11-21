<?php

namespace game;

require_once 'Game.php';
require_once 'Direction.php';
require_once 'Room.php';
require_once __DIR__.'/../playable/index.php';
use \Exception;

/**
 *  The GameBuilder class helps automate building a Game by using
 *  the factory/builder design pattern. Each method will return
 *  the same GameBuilder instance so that you can continue calling
 *  methods on the object, allowing for simpler code to build a
 *  map. Call getGame() when you are finished.
 **/
class GameBuilder
{
  protected static $gameBuilders;
  protected $game = null;

  protected function __construct($gameName = null)
  {
    $this->game = new Game($gameName);
  }

  public static function newGame($gameName)
  {
    if (is_null(self::$gameBuilders))
      self::$gameBuilders = array();
    return self::$gameBuilders[$gameName] = new GameBuilder($gameName);
  }

  // public static function editGame($gameName)
  // {
  //   if (is_null(self::$gameBuilders))
  //     self::$gameBuilders = array();
  //   return self::$gameBuilders[$gameName] = new GameBuilder();
  // }

  public function insertRoom($room)
  {
    $this->game->addRoom($room);
    if (!$this->game->getSpawnPoint())
      $room->define(function ($room) {
        $room->setSpawnPoint();
      });
    return $this;
  }

  // public function setRoomDirectionDescription($roomName, $roomDirection, $roomDirectionDescription)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $roomDirection = $room->getDirection(Direction::cardinalDirection($roomDirection));
  //   $roomDirection->description = $roomDirectionDescription;
  //   return $this;
  // }

  public function connectRooms($roomName1, $room1Direction, $roomName2)
  {
    $room1 = $this->game->getRoom($roomName1);
    if ($room1 == null)
      throw new Exception("Room '$roomName1' not found");
    $room2 = $this->game->getRoom($roomName2);
    if ($room2 == null)
      throw new Exception("Room '$roomName2' not found");
    $room1Direction = Direction::cardinalDirection($room1Direction);
    $room2Direction = Direction::oppositeDirection($room1Direction);
    $room1->getDirection($room1Direction)->setNextRoom($room2);
    $room2->getDirection($room2Direction)->setNextRoom($room1);
    // if ($room1->getDirection($room1Direction)->obstacleItem !== null) {
    //   $itemName = $room1->getDirection($room1Direction)->obstacleItem;
    //   $item = $room1->items[$itemName];
    //   //copy item to $room2
    //   $room2->items[$itemName] = $item;
    //   //assign obstacle to $room2
    //   $room2->directions->getDirection($room2Direction)->obstacleItem = $itemName;
    // }
    // else if ($room2->getDirection($room2Direction)->obstacleItem !== null) {
    //   $itemName = $room2->getDirection($room2Direction)->obstacleItem;
    //   $item = $room2->items[$itemName];
    //   //copy item to $room1
    //   $room1->items[$itemName] = $item;
    //   //assign obstacle to $room1
    //   $room1->getDirection($room1Direction)->obstacleItem = $itemName;
    // }
    return $this;
  }

  public function setSpawnPoint($roomName)
  {
    foreach ($this->game->rooms as $r => $room)
    {
      if ($room->getName() === $roomName)
        $room->define(function ($room) {
          $room->setSpawnPoint();
        });
      else
      $room->define(function ($room) {
        $room->unsetSpawnPoint();
      });
    }
    return $this;
  }

  public function getGame()
  {
    return $this->game;
  }

  public static function getNamedGame($gameName)
  {
    if (isset(self::$gameBuilders) && array_key_exists($gameName, self::$gameBuilders))
      return self::$gameBuilders[$gameName]->getGame();
    return FALSE;
  }

  // public function insertObjectInRoom($roomName, $itemName, $item)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $room->setItem($itemName, $item);
  //   return $this;
  // }
  //
  // public function insertObstacleObjectInRoom($roomName, $roomDirection, $itemName, $item)
  // {
  //   $room = $this->game->getRoom($roomName);
  //   $room->getDirection($roomDirection)->obstacleItem = $itemName;
  //   $room->setItem($itemName, $item);
  //   if ($room->getDirection($roomDirection)->getNextRoom() !== "") {
  //     $room2 = $this->game->getRoom($room->getDirection($roomDirection)->getNextRoomName());
  //     //copy item to room2
  //     $room2->setItem($itemName, $item);
  //     $room2Direction = Direction::oppositeDirection($roomDirection);
  //     //assign collision object to other room
  //     $room2->getDirection($room2Direction)->obstacleItem = $itemName;
  //   }
  //   return $this;
  // }
}
