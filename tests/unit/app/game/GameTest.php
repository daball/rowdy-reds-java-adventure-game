<?php

namespace game\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/Game.php';
require_once __DIR__.'/../../../../app/game/Room.php';

use \game\Game;
use \game\Room;

class GameTest extends \PHPUnit_Framework_TestCase
{
  public function testGame()
  {
    //build map with rooms
    $map = new Game('testGame');
    $room1 = new Room("room1");
    $room2 = new Room("room2");
    $room3 = new Room("room3");
    $room3->setSpawnPoint();
    $room4 = new Room("room4");
    $room5 = new Room("room5");
    $map->addRoom($room1);
    $map->addRoom($room2);
    $map->addRoom($room3);
    $map->addRoom($room4);
    $map->addRoom($room5);

    //test map recall room by name
    $this->assertEquals($room1->getName(), $map->getRoom($room1->getName())->getName());
    $this->assertEquals($room2->getName(), $map->getRoom($room2->getName())->getName());
    $this->assertEquals($room3->getName(), $map->getRoom($room3->getName())->getName());
    $this->assertEquals($room4->getName(), $map->getRoom($room4->getName())->getName());
    $this->assertEquals($room5->getName(), $map->getRoom($room5->getName())->getName());
    $this->assertEquals($room3->getName(), $map->getSpawnPoint());
  }
}
