<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/Room.php';

///Unit tests Room class
class RoomTest extends PHPUnit_Framework_TestCase
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

    $room->directions['n']->jumpTo = $N;
    $room->directions['s']->jumpTo = $S;
    $room->directions['e']->jumpTo = $E;
    $room->directions['w']->jumpTo = $W;

    $room->directions['n']->description = $Nd;
    $room->directions['s']->description = $Sd;
    $room->directions['e']->description = $Ed;
    $room->directions['w']->description = $Wd;

    //test room properties
    $this->assertEquals($roomName, $room->name);
    $this->assertEquals($roomDescription, $room->description);
    $this->assertEquals($roomImageUrl, $room->imageUrl);

    $this->assertEquals($N, $room->directions['n']->jumpTo);
    $this->assertEquals($S, $room->directions['s']->jumpTo);
    $this->assertEquals($E, $room->directions['e']->jumpTo);
    $this->assertEquals($W, $room->directions['w']->jumpTo);

    $this->assertEquals($Nd, $room->directions['n']->description);
    $this->assertEquals($Sd, $room->directions['s']->description);
    $this->assertEquals($Ed, $room->directions['e']->description);
    $this->assertEquals($Wd, $room->directions['w']->description);
  }
}
