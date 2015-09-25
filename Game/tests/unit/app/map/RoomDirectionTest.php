<?php

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/RoomDirection.php';

///Unit tests RoomDirection class
class RoomDirectionTest extends PHPUnit_Framework_TestCase
{
  public function testRoomDirection()
  {
    //define values
    $nextRoom = "roomName";
    $description = "There is a room in this direction called roomName.";

    //create room direction
    $roomDirection = new RoomDirection();

    //set room direction properties
    $roomDirection->nextRoom = $nextRoom;
    $roomDirection->description = $description;

    //test room direction properties
    $this->assertEquals($nextRoom, $roomDirection->nextRoom);
    $this->assertEquals($description, $roomDirection->description);
  }
}
