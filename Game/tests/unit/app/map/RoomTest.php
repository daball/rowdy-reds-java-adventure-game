<?php

namespace map;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/Room.php';

use \map\Room;

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

    $U = "UpRoom";
    $Ud = "This room is to the up.";

    $D = "DownRoom";
    $Dd = "This room is to the down.";

    //create room
    $room = (new Room($roomName))->define(function ($room)
      use ($roomDescription, $roomImageUrl,
            $N, $S, $E, $W, $U, $D, $Nd, $Sd, $Ed, $Wd, $Ud, $Dd) {
      //set room properties
      $room->getComponent('Inspector')->onInspect(function ($inspector) use ($roomDescription) {
        return $roomDescription;
      });
      $room->setImageUrl($roomImageUrl);

      $room->getDirection('n')->nextRoom = $N;
      $room->getDirection('s')->nextRoom = $S;
      $room->getDirection('e')->nextRoom = $E;
      $room->getDirection('w')->nextRoom = $W;
      $room->getDirection('u')->nextRoom = $U;
      $room->getDirection('d')->nextRoom = $D;

      $room->getDirection('n')->description = $Nd;
      $room->getDirection('s')->description = $Sd;
      $room->getDirection('e')->description = $Ed;
      $room->getDirection('w')->description = $Wd;
      $room->getDirection('u')->description = $Ud;
      $room->getDirection('d')->description = $Dd;
    });

    //test room properties
    $this->assertEquals($roomName, $room->getName());
    $this->assertEquals($roomDescription, $room->getComponent('Inspector')->inspect());
    $this->assertEquals($roomImageUrl, $room->getImageUrl());

    $this->assertEquals($N, $room->getDirection('n')->nextRoom);
    $this->assertEquals($S, $room->getDirection('s')->nextRoom);
    $this->assertEquals($E, $room->getDirection('e')->nextRoom);
    $this->assertEquals($W, $room->getDirection('w')->nextRoom);
    $this->assertEquals($U, $room->getDirection('u')->nextRoom);
    $this->assertEquals($D, $room->getDirection('d')->nextRoom);

    $this->assertEquals($Nd, $room->getDirection('n')->description);
    $this->assertEquals($Sd, $room->getDirection('s')->description);
    $this->assertEquals($Ed, $room->getDirection('e')->description);
    $this->assertEquals($Wd, $room->getDirection('w')->description);
    $this->assertEquals($Ud, $room->getDirection('u')->description);
    $this->assertEquals($Dd, $room->getDirection('d')->description);
  }
}
