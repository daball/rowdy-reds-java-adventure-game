<?php

namespace game\test;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/Room.php';

use \game\Room;

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

      $room->getDirection('n')->setNextRoomName($N);
      $room->getDirection('s')->setNextRoomName($S);
      $room->getDirection('e')->setNextRoomName($E);
      $room->getDirection('w')->setNextRoomName($W);
      $room->getDirection('u')->setNextRoomName($U);
      $room->getDirection('d')->setNextRoomName($D);

      $room->getDirection('n')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Nd) {
              return $Nd;
            }
      );
      $room->getDirection('s')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Sd) {
              return $Sd;
            }
          );
      $room->getDirection('e')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Ed) {
              return $Ed;
            }
      );
      $room->getDirection('w')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Wd) {
              return $Wd;
            }
      );
      $room->getDirection('u')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Ud) {
              return $Ud;
            }
      );
      $room->getDirection('d')
            ->getComponent("Inspector")
            ->onInspect(
            function ($inspector) use ($Dd) {
              return $Dd;
            }
      );
    });

    //test room properties
    $this->assertEquals($roomName, $room->getName());
    $this->assertEquals($roomDescription, $room->getComponent('Inspector')->inspect());
    $this->assertEquals($roomDescription, $room->inspectRoom());
    $this->assertEquals($roomImageUrl, $room->getImageUrl());

    $this->assertEquals($N, $room->getRoomNameAtDirection('n'));
    $this->assertEquals($S, $room->getRoomNameAtDirection('s'));
    $this->assertEquals($E, $room->getRoomNameAtDirection('e'));
    $this->assertEquals($W, $room->getRoomNameAtDirection('w'));
    $this->assertEquals($U, $room->getRoomNameAtDirection('u'));
    $this->assertEquals($D, $room->getRoomNameAtDirection('d'));

    $this->assertEquals($Nd, $room->inspectDirection('n'));
    $this->assertEquals($Sd, $room->inspectDirection('s'));
    $this->assertEquals($Ed, $room->inspectDirection('e'));
    $this->assertEquals($Wd, $room->inspectDirection('w'));
    $this->assertEquals($Ud, $room->inspectDirection('u'));
    $this->assertEquals($Dd, $room->inspectDirection('d'));
  }
}
