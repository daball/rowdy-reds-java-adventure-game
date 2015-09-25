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

  public function __construct()
  {
    $this->map = new Map();
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
    $room->description = $roomDescription;
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
    $roomDirection = $room->directions[Direction::getDirection($roomDirection)];
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

  public function addItemToRoom($roomName, $item)
  {
    $room = $this->map->getRoom($roomName);
    array_push($room->items, $item);
    return $this;
  }

  public function insertDoorObstacle($roomName, $roomDirection, $doorName)
  {
    $room = $this->map->getRoom($roomName);
    $door = new \playable\Door();
    $door->close();
    $room->directions->getDirection($roomDirection)->obstacleItem = $doorName;
    $room->items[$doorName] = $door;
    return $this;
  }
}
