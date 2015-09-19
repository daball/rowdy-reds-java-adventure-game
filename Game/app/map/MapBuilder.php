<?php

require_once 'Map.php';
require_once 'Room.php';
require_once 'Direction.php';

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
    $room1Direction = Direction::getDirection($room1Direction);
    $room2Direction = Direction::oppositeDirection($room1Direction);
    $room1->directions[$room1Direction]->jumpTo = $room2->name;
    $room2->directions[$room2Direction]->jumpTo = $room1->name;
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
}
