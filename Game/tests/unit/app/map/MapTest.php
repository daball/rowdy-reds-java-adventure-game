<?php

namespace map\tests;
use \map\Map;
use \map\Room;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/Room.php';
require_once __DIR__.'/../../../../app/map/Map.php';

///Unit tests Map class
class MapTest extends \PHPUnit_Framework_TestCase
{
  public function testMap()
  {
    //build map with rooms
    $map = new Map();
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
