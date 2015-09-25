<?php

namespace map\tests;
use \map\Map;
use \map\Room;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/Room.php';
require_once __DIR__.'/../../../../app/map/Map.php';

///Unit tests Map class
class MapTest extends PHPUnit_Framework_TestCase
{
  public function testMap()
  {
    //build map with rooms
    $map = new Map();
    $room1 = new Room();
    $room1->name = "room1";
    $room2 = new Room();
    $room2->name = "room2";
    $room3 = new Room();
    $room3->name = "room3";
    $room3->spawn = true;
    $room4 = new Room();
    $room4->name = "room4";
    $room5 = new Room();
    $room5->name = "room5";
    $map->addRoom($room1);
    $map->addRoom($room2);
    $map->addRoom($room3);
    $map->addRoom($room4);
    $map->addRoom($room5);

    //test map recall room by name
    $this->assertEquals($room1->name, $map->getRoom($room1->name)->name);
    $this->assertEquals($room2->name, $map->getRoom($room2->name)->name);
    $this->assertEquals($room3->name, $map->getRoom($room3->name)->name);
    $this->assertEquals($room4->name, $map->getRoom($room4->name)->name);
    $this->assertEquals($room5->name, $map->getRoom($room5->name)->name);
    $this->assertEquals($room3->name, $map->getSpawnPoint());
  }
}
