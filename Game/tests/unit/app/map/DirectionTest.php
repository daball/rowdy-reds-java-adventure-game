<?php

namespace map\tests;
use \map\Direction;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/Direction.php';

///Unit tests Direction class
class DirectionTest extends PHPUnit_Framework_TestCase
{
  public function testRoomDirection()
  {
    //define values
    $nextRoom = "roomName";
    $description = "There is a room in this direction called roomName.";

    //create room direction
    $roomDirection = new Direction();

    //set room direction properties
    $roomDirection->nextRoom = $nextRoom;
    $roomDirection->description = $description;

    //test room direction properties
    $this->assertEquals($nextRoom, $roomDirection->nextRoom);
    $this->assertEquals($description, $roomDirection->description);
  }
  public function testNorth()
  {
    $this->assertEquals("n", Direction::$n);
    $this->assertEquals("n", Direction::$north);
    $this->assertEquals("n", Direction::getDirection("n"));
    $this->assertEquals("n", Direction::getDirection("N"));
    $this->assertEquals("n", Direction::getDirection("north"));
    $this->assertEquals("n", Direction::getDirection("North"));
    $this->assertEquals("n", Direction::getDirection("nOrth"));
    $this->assertEquals("n", Direction::getDirection("noRth"));
    $this->assertEquals("n", Direction::getDirection("NOrth"));
    $this->assertEquals("n", Direction::getDirection("NORth"));
    $this->assertEquals("n", Direction::getDirection("NORTh"));
    $this->assertEquals("n", Direction::getDirection("NORTH"));
  }
  public function testSouth()
  {
    $this->assertEquals("s", Direction::$s);
    $this->assertEquals("s", Direction::$south);
    $this->assertEquals("s", Direction::getDirection("s"));
    $this->assertEquals("s", Direction::getDirection("S"));
    $this->assertEquals("s", Direction::getDirection("south"));
    $this->assertEquals("s", Direction::getDirection("South"));
    $this->assertEquals("s", Direction::getDirection("sOuth"));
    $this->assertEquals("s", Direction::getDirection("soUth"));
    $this->assertEquals("s", Direction::getDirection("SOuth"));
    $this->assertEquals("s", Direction::getDirection("SOUth"));
    $this->assertEquals("s", Direction::getDirection("SOUTh"));
    $this->assertEquals("s", Direction::getDirection("SOUTH"));
  }
  public function testEast()
  {
    $this->assertEquals("e", Direction::$e);
    $this->assertEquals("e", Direction::$east);
    $this->assertEquals("e", Direction::getDirection("e"));
    $this->assertEquals("e", Direction::getDirection("E"));
    $this->assertEquals("e", Direction::getDirection("east"));
    $this->assertEquals("e", Direction::getDirection("East"));
    $this->assertEquals("e", Direction::getDirection("eAst"));
    $this->assertEquals("e", Direction::getDirection("eaSt"));
    $this->assertEquals("e", Direction::getDirection("EAst"));
    $this->assertEquals("e", Direction::getDirection("EASt"));
    $this->assertEquals("e", Direction::getDirection("eaST"));
    $this->assertEquals("e", Direction::getDirection("EAST"));
  }
  public function testWest()
  {
    $this->assertEquals("w", Direction::$w);
    $this->assertEquals("w", Direction::$west);
    $this->assertEquals("w", Direction::getDirection("w"));
    $this->assertEquals("w", Direction::getDirection("W"));
    $this->assertEquals("w", Direction::getDirection("west"));
    $this->assertEquals("w", Direction::getDirection("West"));
    $this->assertEquals("w", Direction::getDirection("wEst"));
    $this->assertEquals("w", Direction::getDirection("weSt"));
    $this->assertEquals("w", Direction::getDirection("WEst"));
    $this->assertEquals("w", Direction::getDirection("WESt"));
    $this->assertEquals("w", Direction::getDirection("weST"));
    $this->assertEquals("w", Direction::getDirection("WEST"));
  }
  public function testOppositeNorth()
  {
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$n));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$north));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("n")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("N")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("north")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("North")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("nOrth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("noRth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("NOrth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("NORth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("NORTh")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::getDirection("NORTH")));
  }
  public function testOppositeSouth()
  {
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$s));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$south));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("s")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("S")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("south")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("South")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("sOuth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("soUth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("SOuth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("SOUth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("SOUTh")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::getDirection("SOUTH")));
  }
  public function testOppositeEast()
  {
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$e));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$east));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("e")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("E")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("east")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("East")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("eAst")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("eaSt")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("EAst")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("EASt")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("eaST")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::getDirection("EAST")));
  }
  public function testOppositeWest()
  {
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$w));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$west));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("w")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("W")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("west")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("West")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("wEst")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("weSt")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("WEst")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("WESt")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("weST")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::getDirection("WEST")));
  }
}
