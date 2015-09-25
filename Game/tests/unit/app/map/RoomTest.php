<?php

namespace map;
use \playable\Room;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/playable/Room.php';

///Unit tests Room class
class RoomTest extends \PHPUnit_Framework_TestCase
{
  public function testRoom()
  {
    //define values
    $roomName = "MyRoom";
    $roomDescription = "This is my room.";
    $roomImageUrl = "myRoom.jpg";

    $N = "NorthRoom";
    $Nd = "This room is to the north.";

    $S = "SouthRoom";
    $Sd = "This room is to the south.";

    $E = "EastRoom";
    $Ed = "This room is to the east.";

    $W = "WestRoom";
    $Wd = "This room is to the west.";

    //create room
    $room = new Room();

    //set room properties
    $room->name = $roomName;
    $room->description = $roomDescription;
    $room->imageUrl = $roomImageUrl;

    $room->directions->getDirection('n')->nextRoom = $N;
    $room->directions->getDirection('s')->nextRoom = $S;
    $room->directions->getDirection('e')->nextRoom = $E;
    $room->directions->getDirection('w')->nextRoom = $W;

    $room->directions->getDirection('n')->description = $Nd;
    $room->directions->getDirection('s')->description = $Sd;
    $room->directions->getDirection('e')->description = $Ed;
    $room->directions->getDirection('w')->description = $Wd;

    //test room properties
    $this->assertEquals($roomName, $room->name);
    $this->assertEquals($roomDescription, $room->description);
    $this->assertEquals($roomImageUrl, $room->imageUrl);

    $this->assertEquals($N, $room->directions->getDirection('n')->nextRoom);
    $this->assertEquals($S, $room->directions->getDirection('s')->nextRoom);
    $this->assertEquals($E, $room->directions->getDirection('e')->nextRoom);
    $this->assertEquals($W, $room->directions->getDirection('w')->nextRoom);

    $this->assertEquals($Nd, $room->directions->getDirection('n')->description);
    $this->assertEquals($Sd, $room->directions->getDirection('s')->description);
    $this->assertEquals($Ed, $room->directions->getDirection('e')->description);
    $this->assertEquals($Wd, $room->directions->getDirection('w')->description);
  }
}
