<?php

require_once 'Room.php';

///Defines a Map which contains multiple Rooms
class Map
{
  //Do yourself a favor and don't manipulate this manually.
  //It is manipulated by the app framework, but you shouldn't change it
  //directly. It is provided for the purpose of iteration.
  public $rooms = array();

  //Pushes the room onto the end of the room array
  public function addRoom(Room $room)
  {
    $this->rooms[$room->name] = $room;
  }

  ///Gets the room by the roomName from the rooms array
  public function getRoom($roomName)
  {
    return $this->rooms[$roomName];
  }

  ///Gets the room name where spawn is true
  public function getSpawnPoint()
  {
    foreach ($this->rooms as $r => $room)
    {
      if ($room->spawn)
        return $room->name;
    }
  }

  public function serialize() {
    return serialize(
      array(
        'rooms' => $this->rooms
      )
    );
  }

  public function unserialize($data) {
    $data = unserialize($data);
    $this->rooms = $data['rooms'];
  }
}
