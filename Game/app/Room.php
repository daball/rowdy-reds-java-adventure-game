<?php

require_once 'RoomDirection.php';

///Defines a Room in the Game
class Room
{
  ///Name of the room (used internally as a reference)
  ///Example: darkRoomA1
  public $name = "";
  ///Description of the room
  ///Example: You are in a dark room. To your south, you see a bright light. You wonder what lurks about.
  public $description = "";
  ///Image path (URL)
  ///Example: roomA1.jpg
  public $imageUrl = "";
  ///Is this the spawn point?
  ///Example: true/false
  public $spawn = false;
  ///Associative array of room directions
  ///n = RoomDirection object for the north direction
  ///s = RoomDirection object for the south direction
  ///e = RoomDirection object for the east direction
  ///w = RoomDirection object for the west direction
  public $directions = array(
    'n' => null,
    's' => null,
    'e' => null,
    'w' => null,
  );

  public function __construct()
  {
    foreach ($this->directions as $d => $dir) {
      $this->directions[$d] = new RoomDirection();
    }
  }
}
