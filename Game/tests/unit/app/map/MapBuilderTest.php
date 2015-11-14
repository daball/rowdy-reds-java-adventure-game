<?php

namespace map\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/MapBuilder.php';
require_once __DIR__.'/../../../../app/map/Room.php';

use \map\MapBuilder;
use \map\Room;

class MapBuilderTest extends \PHPUnit_Framework_TestCase
{
  public function testMapBuilder()
  {
    //define rooms
    $room1 = array(
      'name' => 'Room1',
      'description' => 'It is the first room.',
      'imageUrl' => 'room1.jpg'
    );
    $room2 = array(
      'name' => 'Room2',
      'description' => 'It is the second room, to the north.',
      'imageUrl' => 'room2.jpg'
    );
    $room3 = array(
      'name' => 'Room3',
      'description' => 'It is the third room, to the south.',
      'imageUrl' => 'room3.jpg'
    );
    $room4 = array(
      'name' => 'Room4',
      'description' => 'It is the fourth room, to the east.',
      'imageUrl' => 'room4.jpg'
    );
    $room5 = array(
      'name' => 'Room5',
      'description' => 'It is the fifth room, to the west.',
      'imageUrl' => 'room5.jpg'
    );

    //build map with rooms
    $mapBuilder = (new MapBuilder())
            ->insertRoom((new Room($room1['name']))->define(function ($room) use ($room1) {
              $room->getComponent('Inspector')->onInspect(function ($inspector) use ($room1) {
                return $room1['description'];
              });
              $room->setImageUrl($room1['imageUrl']);
              // ->setRoomDirectionDescription($room1['name'], 'n', $room2['description'])
              // ->setRoomDirectionDescription($room1['name'], 's', $room3['description'])
              // ->setRoomDirectionDescription($room1['name'], 'e', $room4['description'])
              // ->setRoomDirectionDescription($room1['name'], 'w', $room5['description'])
            }))
            ->insertRoom((new Room($room2['name']))->define(function ($room) use ($room2) {
              $room->getComponent('Inspector')->onInspect(function ($inspector) use ($room2) {
                return $room2['description'];
              });
              $room->setImageUrl($room2['imageUrl']);
              // ->setRoomDirectionDescription($room2['name'], 's', $room1['description']);
            }))
            ->insertRoom((new Room($room3['name']))->define(function ($room) use ($room3) {
              $room->getComponent('Inspector')->onInspect(function ($inspector) use ($room3) {
                return $room3['description'];
              });
              $room->setImageUrl($room3['imageUrl']);
              // ->setRoomDirectionDescription($room3['name'], 'n', $room1['description']);
            }))
            ->insertRoom((new Room($room4['name']))->define(function ($room) use ($room4) {
              $room->getComponent('Inspector')->onInspect(function ($inspector) use ($room4) {
                return $room4['description'];
              });
              $room->setImageUrl($room4['name'], $room4['imageUrl']);
              // ->setRoomDirectionDescription($room4['name'], 'w', $room1['description']);
            }))
            ->insertRoom((new Room($room5['name']))->define(function ($room) use ($room5) {
              $room->getComponent('Inspector')->onInspect(function ($inspector) use ($room5) {
                return $room5['description'];
              });
              $room->setImageUrl($room5['name'], $room5['imageUrl']);
              // ->setRoomDirectionDescription($room5['name'], 'e', $room1['description']);
            }))
            ->connectRooms($room1['name'], 'n', $room2['name'])
            ->connectRooms($room1['name'], 's', $room3['name'])
            ->connectRooms($room1['name'], 'e', $room4['name'])
            ->connectRooms($room1['name'], 'w', $room5['name'])
            ;
    $map = $mapBuilder->getMap();

    //test map
    $this->assertEquals($room1['name'], $map->getRoom($room1['name'])->getName());
    $this->assertEquals($room2['name'], $map->getRoom($room2['name'])->getName());
    $this->assertEquals($room3['name'], $map->getRoom($room3['name'])->getName());
    $this->assertEquals($room4['name'], $map->getRoom($room4['name'])->getName());

    $this->assertEquals($room1['description'], $map->getRoom($room1['name'])->getComponent('Inspector')->inspect());
    $this->assertEquals($room2['description'], $map->getRoom($room2['name'])->getComponent('Inspector')->inspect());
    $this->assertEquals($room3['description'], $map->getRoom($room3['name'])->getComponent('Inspector')->inspect());
    $this->assertEquals($room4['description'], $map->getRoom($room4['name'])->getComponent('Inspector')->inspect());

    $this->assertEquals($room1['imageUrl'], $map->getRoom($room1['name'])->getImageUrl());
    $this->assertEquals($room2['imageUrl'], $map->getRoom($room2['name'])->getImageUrl());
    $this->assertEquals($room3['imageUrl'], $map->getRoom($room3['name'])->getImageUrl());
    $this->assertEquals($room4['imageUrl'], $map->getRoom($room4['name'])->getImageUrl());

    $this->assertEquals($room2['description'], $map->getRoom($room1['name'])->getDirection('n')->description);
    $this->assertEquals($room3['description'], $map->getRoom($room1['name'])->getDirection('s')->description);
    $this->assertEquals($room4['description'], $map->getRoom($room1['name'])->getDirection('e')->description);
    $this->assertEquals($room5['description'], $map->getRoom($room1['name'])->getDirection('w')->description);

    $this->assertEquals($room1['description'], $map->getRoom($room2['name'])->getDirection('s')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room3['name'])->getDirection('n')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room4['name'])->getDirection('w')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room5['name'])->getDirection('e')->description);

    //test forward direction
    $this->assertEquals($room2['name'], $map->getRoom($room1['name'])->getDirection('n')->nextRoom);
    $this->assertEquals($room3['name'], $map->getRoom($room1['name'])->getDirection('s')->nextRoom);
    $this->assertEquals($room4['name'], $map->getRoom($room1['name'])->getDirection('e')->nextRoom);
    $this->assertEquals($room5['name'], $map->getRoom($room1['name'])->getDirection('w')->nextRoom);

    //test reverse direction
    $this->assertEquals($room1['name'], $map->getRoom($room2['name'])->getDirection('s')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room3['name'])->getDirection('n')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room4['name'])->getDirection('w')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room5['name'])->getDirection('e')->nextRoom);

    //test spawn location
    $this->assertTrue($map->getRoom($room1['name'])->isSpawnPoint());
    $this->assertEquals($room1['name'], $map->getSpawnPoint());

    //change spawn location
    $mapBuilder->setSpawnPoint($room2['name']);
    $map = $mapBuilder->getMap();
    $this->assertFalse($map->getRoom($room1['name'])->isSpawnPoint());
    $this->assertTrue($map->getRoom($room2['name'])->isSpawnPoint());
    $this->assertEquals($room2['name'], $map->getSpawnPoint());
  }
}
