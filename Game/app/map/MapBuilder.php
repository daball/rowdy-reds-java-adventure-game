<?php

namespace map;

use \playable\Room;

require_once 'Map.php';
require_once 'Direction.php';
require_once __DIR__.'/../playable/index.php';

///The MapBuilder class helps automate building a Map by using
///the factory/builder design pattern. Each method will return
///the same MapBuilder instance so that you can continue calling
///methods on the object, allowing for simpler code to build a
///map.
class MapBuilder
{
  private $map = null;

  public function __construct($map = null)
  {
    if ($map == null) $map = new Map();
    $this->map = $map;
  }

  public function createRoom($roomName)
  {
    $room = new Room();
    $room->name = $roomName;
    if (sizeof($this->map->rooms) == 0)
      $room->spawn = true;
    $this->map->addRoom($room);
    return $this;
  }

  public function setRoomDescription($roomName, $roomDescription)
  {
    $room = $this->map->getRoom($roomName);
    $room->setDescription($roomDescription);
    return $this;
  }

  public function setRoomImageUrl($roomName, $roomImageUrl)
  {
    $this->map->getRoom($roomName)->imageUrl = $roomImageUrl;
    return $this;
  }

  public function setRoomDirectionDescription($roomName, $roomDirection, $roomDirectionDescription)
  {
    $room = $this->map->getRoom($roomName);
    $roomDirection = $room->directions->getDirection(Direction::cardinalDirection($roomDirection));
    $roomDirection->description = $roomDirectionDescription;
    return $this;
  }


  public function connectRooms($roomName1, $room1Direction, $roomName2)
  {
    $room1 = $this->map->getRoom($roomName1);
    $room2 = $this->map->getRoom($roomName2);
    $room1Direction = Direction::cardinalDirection($room1Direction);
    $room2Direction = Direction::oppositeDirection($room1Direction);
    $room1->directions->getDirection($room1Direction)->nextRoom = $room2->name;
    $room2->directions->getDirection($room2Direction)->nextRoom = $room1->name;
    if ($room1->directions->getDirection($room1Direction)->obstacleItem !== null) {
      $itemName = $room1->directions->getDirection($room1Direction)->obstacleItem;
      $item = $room1->items[$itemName];
      //copy item to $room2
      $room2->items[$itemName] = $item;
      //assign obstacle to $room2
      $room2->directions->getDirection($room2Direction)->obstacleItem = $itemName;
    }
    else if ($room2->directions->getDirection($room2Direction)->obstacleItem !== null) {
      $itemName = $room2->directions->getDirection($room2Direction)->obstacleItem;
      $item = $room2->items[$itemName];
      //copy item to $room1
      $room1->items[$itemName] = $item;
      //assign obstacle to $room1
      $room1->directions->getDirection($room1Direction)->obstacleItem = $itemName;
    }
    return $this;
  }

  public function setSpawnPoint($roomName)
  {
    foreach ($this->map->rooms as $r => $room)
    {
      if ($room->name === $roomName)
        $room->spawn = true;
      else
        $room->spawn = false;
    }
    return $this;
  }

  public function getMap()
  {
    return $this->map;
  }

  public function insertObjectInRoom($roomName, $itemName, $item)
  {
    $room = $this->map->getRoom($roomName);
    $room->items[$itemName] = $item;
    return $this;
  }

  public function insertObstacleObjectInRoom($roomName, $roomDirection, $itemName, $item)
  {
    $room = $this->map->getRoom($roomName);
    $room->directions->getDirection($roomDirection)->obstacleItem = $itemName;
    $room->items[$itemName] = $item;
    if ($room->directions->getDirection($roomDirection)->nextRoom !== "") {
      $room2 = $this->map->getRoom($room->directions->getDirection($roomDirection)->nextRoom);
      //copy item to room2
      $room2->items[$itemName] = $item;
      $room2Direction = Direction::oppositeDirection($roomDirection);
      //assign collision object to other room
      $room2->directions->getDirection($room2Direction)->obstacleItem = $itemName;
    }
    return $this;
  }
}
