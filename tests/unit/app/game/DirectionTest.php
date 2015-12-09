<?php

namespace game\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/Direction.php';

use \game\Direction;

class DirectionTest extends \PHPUnit_Framework_TestCase
{
  public function testRoomDirection()
  {
    //define values
    $nextRoom = "roomName";
    $obvious = false;
    $description = "There is a room in this direction called roomName.";

    //create room direction
    $roomDirection = new Direction('n');

    //set room direction properties
    $roomDirection->setNextRoomName($nextRoom);
    $roomDirection->getComponent("Inspector")->onInspect(function ($inspector) use ($description) {
      return $description;
    });

    //test room direction properties
    $this->assertEquals($nextRoom, $roomDirection->getNextRoomName());
    $this->assertEquals($description, $roomDirection->getComponent("Inspector")->inspect());
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
  public function testUp() {
    $this->assertEquals("u", Direction::$u);
    $this->assertEquals("u", Direction::$up);
    $this->assertEquals("u", Direction::cardinalDirection("u"));
    $this->assertEquals("u", Direction::cardinalDirection("up"));
    $this->assertEquals("u", Direction::cardinalDirection("uP"));
    $this->assertEquals("u", Direction::cardinalDirection("U"));
    $this->assertEquals("u", Direction::cardinalDirection("Up"));
    $this->assertEquals("u", Direction::cardinalDirection("UP"));
  }
  public function testDown() {
    $this->assertEquals("d", Direction::$d);
    $this->assertEquals("d", Direction::$down);
    $this->assertEquals("d", Direction::cardinalDirection("d"));
    $this->assertEquals("d", Direction::cardinalDirection("D"));
    $this->assertEquals("d", Direction::cardinalDirection("down"));
    $this->assertEquals("d", Direction::cardinalDirection("DOWN"));
    $this->assertEquals("d", Direction::cardinalDirection("doWN"));
    $this->assertEquals("d", Direction::cardinalDirection("DOwn"));
  }
  public function testOppositeNorth()
  {
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$n));
    $this->assertEquals("s", Direction::oppositeDirection(Direction::$north));
    $this->assertEquals("s", Direction::oppositeDirection("n"));
    $this->assertEquals("s", Direction::oppositeDirection("N"));
    $this->assertEquals("s", Direction::oppositeDirection("north"));
    $this->assertEquals("s", Direction::oppositeDirection("North"));
    $this->assertEquals("s", Direction::oppositeDirection("nOrth"));
    $this->assertEquals("s", Direction::oppositeDirection("noRth"));
    $this->assertEquals("s", Direction::oppositeDirection("NOrth"));
    $this->assertEquals("s", Direction::oppositeDirection("NORth"));
    $this->assertEquals("s", Direction::oppositeDirection("NORTh"));
    $this->assertEquals("s", Direction::oppositeDirection("NORTH"));
  }
  public function testOppositeSouth()
  {
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$s));
    $this->assertEquals("n", Direction::oppositeDirection(Direction::$south));
    $this->assertEquals("n", Direction::oppositeDirection("s"));
    $this->assertEquals("n", Direction::oppositeDirection("S"));
    $this->assertEquals("n", Direction::oppositeDirection("south"));
    $this->assertEquals("n", Direction::oppositeDirection("South"));
    $this->assertEquals("n", Direction::oppositeDirection("sOuth"));
    $this->assertEquals("n", Direction::oppositeDirection("soUth"));
    $this->assertEquals("n", Direction::oppositeDirection("SOuth"));
    $this->assertEquals("n", Direction::oppositeDirection("SOUth"));
    $this->assertEquals("n", Direction::oppositeDirection("SOUTh"));
    $this->assertEquals("n", Direction::oppositeDirection("SOUTH"));
  }
  public function testOppositeEast()
  {
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$e));
    $this->assertEquals("w", Direction::oppositeDirection(Direction::$east));
    $this->assertEquals("w", Direction::oppositeDirection("e"));
    $this->assertEquals("w", Direction::oppositeDirection("E"));
    $this->assertEquals("w", Direction::oppositeDirection("east"));
    $this->assertEquals("w", Direction::oppositeDirection("East"));
    $this->assertEquals("w", Direction::oppositeDirection("eAst"));
    $this->assertEquals("w", Direction::oppositeDirection("eaSt"));
    $this->assertEquals("w", Direction::oppositeDirection("EAst"));
    $this->assertEquals("w", Direction::oppositeDirection("EASt"));
    $this->assertEquals("w", Direction::oppositeDirection("eaST"));
    $this->assertEquals("w", Direction::oppositeDirection("EAST"));
  }
  public function testOppositeWest()
  {
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$w));
    $this->assertEquals("e", Direction::oppositeDirection(Direction::$west));
    $this->assertEquals("e", Direction::oppositeDirection("w"));
    $this->assertEquals("e", Direction::oppositeDirection("W"));
    $this->assertEquals("e", Direction::oppositeDirection("west"));
    $this->assertEquals("e", Direction::oppositeDirection("West"));
    $this->assertEquals("e", Direction::oppositeDirection("wEst"));
    $this->assertEquals("e", Direction::oppositeDirection("weSt"));
    $this->assertEquals("e", Direction::oppositeDirection("WEst"));
    $this->assertEquals("e", Direction::oppositeDirection("WESt"));
    $this->assertEquals("e", Direction::oppositeDirection("weST"));
    $this->assertEquals("e", Direction::oppositeDirection("WEST"));
  }
  public function testOppositeUp() {
    $this->assertEquals("d", Direction::oppositeDirection(Direction::$u));
    $this->assertEquals("d", Direction::oppositeDirection(Direction::$up));
    $this->assertEquals("d", Direction::oppositeDirection("u"));
    $this->assertEquals("d", Direction::oppositeDirection("up"));
    $this->assertEquals("d", Direction::oppositeDirection("uP"));
    $this->assertEquals("d", Direction::oppositeDirection("U"));
    $this->assertEquals("d", Direction::oppositeDirection("Up"));
    $this->assertEquals("d", Direction::oppositeDirection("UP"));
  }
  public function testOppositeDown() {
    $this->assertEquals("u", Direction::oppositeDirection(Direction::$d));
    $this->assertEquals("u", Direction::oppositeDirection(Direction::$down));
    $this->assertEquals("u", Direction::oppositeDirection("d"));
    $this->assertEquals("u", Direction::oppositeDirection("D"));
    $this->assertEquals("u", Direction::oppositeDirection("down"));
    $this->assertEquals("u", Direction::oppositeDirection("DOWN"));
    $this->assertEquals("u", Direction::oppositeDirection("doWN"));
    $this->assertEquals("u", Direction::oppositeDirection("DOwn"));
  }
  public function testFullDirections() {
    $this->assertEquals("north", Direction::fullDirection(Direction::$n));
    $this->assertEquals("south", Direction::fullDirection(Direction::$s));
    $this->assertEquals("east", Direction::fullDirection(Direction::$e));
    $this->assertEquals("west", Direction::fullDirection(Direction::$w));
    $this->assertEquals("up", Direction::fullDirection(Direction::$u));
    $this->assertEquals("down", Direction::fullDirection(Direction::$d));
  }
}
