<?php

namespace map\tests;
use \map\Direction;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/Direction.php';

///Unit tests Direction class
class DirectionTest extends \PHPUnit_Framework_TestCase
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
    $this->assertEquals("n", Direction::cardinalDirection("n"));
    $this->assertEquals("n", Direction::cardinalDirection("N"));
    $this->assertEquals("n", Direction::cardinalDirection("north"));
    $this->assertEquals("n", Direction::cardinalDirection("North"));
    $this->assertEquals("n", Direction::cardinalDirection("nOrth"));
    $this->assertEquals("n", Direction::cardinalDirection("noRth"));
    $this->assertEquals("n", Direction::cardinalDirection("NOrth"));
    $this->assertEquals("n", Direction::cardinalDirection("NORth"));
    $this->assertEquals("n", Direction::cardinalDirection("NORTh"));
    $this->assertEquals("n", Direction::cardinalDirection("NORTH"));
  }
  public function testSouth()
  {
    $this->assertEquals("s", Direction::$s);
    $this->assertEquals("s", Direction::$south);
    $this->assertEquals("s", Direction::cardinalDirection("s"));
    $this->assertEquals("s", Direction::cardinalDirection("S"));
    $this->assertEquals("s", Direction::cardinalDirection("south"));
    $this->assertEquals("s", Direction::cardinalDirection("South"));
    $this->assertEquals("s", Direction::cardinalDirection("sOuth"));
    $this->assertEquals("s", Direction::cardinalDirection("soUth"));
    $this->assertEquals("s", Direction::cardinalDirection("SOuth"));
    $this->assertEquals("s", Direction::cardinalDirection("SOUth"));
    $this->assertEquals("s", Direction::cardinalDirection("SOUTh"));
    $this->assertEquals("s", Direction::cardinalDirection("SOUTH"));
  }
  public function testEast()
  {
    $this->assertEquals("e", Direction::$e);
    $this->assertEquals("e", Direction::$east);
    $this->assertEquals("e", Direction::cardinalDirection("e"));
    $this->assertEquals("e", Direction::cardinalDirection("E"));
    $this->assertEquals("e", Direction::cardinalDirection("east"));
    $this->assertEquals("e", Direction::cardinalDirection("East"));
    $this->assertEquals("e", Direction::cardinalDirection("eAst"));
    $this->assertEquals("e", Direction::cardinalDirection("eaSt"));
    $this->assertEquals("e", Direction::cardinalDirection("EAst"));
    $this->assertEquals("e", Direction::cardinalDirection("EASt"));
    $this->assertEquals("e", Direction::cardinalDirection("eaST"));
    $this->assertEquals("e", Direction::cardinalDirection("EAST"));
  }
  public function testWest()
  {
    $this->assertEquals("w", Direction::$w);
    $this->assertEquals("w", Direction::$west);
    $this->assertEquals("w", Direction::cardinalDirection("w"));
    $this->assertEquals("w", Direction::cardinalDirection("W"));
    $this->assertEquals("w", Direction::cardinalDirection("west"));
    $this->assertEquals("w", Direction::cardinalDirection("West"));
    $this->assertEquals("w", Direction::cardinalDirection("wEst"));
    $this->assertEquals("w", Direction::cardinalDirection("weSt"));
    $this->assertEquals("w", Direction::cardinalDirection("WEst"));
    $this->assertEquals("w", Direction::cardinalDirection("WESt"));
    $this->assertEquals("w", Direction::cardinalDirection("weST"));
    $this->assertEquals("w", Direction::cardinalDirection("WEST"));
  }
  public function testOppositeNorth()
  {
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$n));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$north));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("n")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("N")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("north")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("North")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("nOrth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("noRth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("NOrth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("NORth")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("NORTh")));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::cardinalDirection("NORTH")));
  }
  public function testOppositeSouth()
  {
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$s));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$south));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("s")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("S")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("south")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("South")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("sOuth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("soUth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("SOuth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("SOUth")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("SOUTh")));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::cardinalDirection("SOUTH")));
  }
  public function testOppositeEast()
  {
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$e));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$east));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("e")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("E")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("east")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("East")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("eAst")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("eaSt")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("EAst")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("EASt")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("eaST")));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::cardinalDirection("EAST")));
  }
  public function testOppositeWest()
  {
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$w));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$west));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("w")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("W")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("west")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("West")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("wEst")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("weSt")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("WEst")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("WESt")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("weST")));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::cardinalDirection("WEST")));
  }
}
