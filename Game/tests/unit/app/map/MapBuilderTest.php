<?php

namespace map\tests;
use \map\MapBuilder;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/map/MapBuilder.php';

///Unit tests MapBuilder class
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
            ->createRoom($room1['name'])
              ->setRoomDescription($room1['name'], $room1['description'])
              ->setRoomImageUrl($room1['name'], $room1['imageUrl'])
              ->setRoomDirectionDescription($room1['name'], 'n', $room2['description'])
              ->setRoomDirectionDescription($room1['name'], 's', $room3['description'])
              ->setRoomDirectionDescription($room1['name'], 'e', $room4['description'])
              ->setRoomDirectionDescription($room1['name'], 'w', $room5['description'])
            ->createRoom($room2['name'])
              ->setRoomDescription($room2['name'], $room2['description'])
              ->setRoomImageUrl($room2['name'], $room2['imageUrl'])
              ->setRoomDirectionDescription($room2['name'], 's', $room1['description'])
            ->createRoom($room3['name'])
              ->setRoomDescription($room3['name'], $room3['description'])
              ->setRoomImageUrl($room3['name'], $room3['imageUrl'])
              ->setRoomDirectionDescription($room3['name'], 'n', $room1['description'])
            ->createRoom($room4['name'])
              ->setRoomDescription($room4['name'], $room4['description'])
              ->setRoomImageUrl($room4['name'], $room4['imageUrl'])
              ->setRoomDirectionDescription($room4['name'], 'w', $room1['description'])
            ->createRoom($room5['name'])
              ->setRoomDescription($room5['name'], $room5['description'])
              ->setRoomImageUrl($room5['name'], $room5['imageUrl'])
              ->setRoomDirectionDescription($room5['name'], 'e', $room1['description'])
            ->connectRooms($room1['name'], 'n', $room2['name'])
            ->connectRooms($room1['name'], 's', $room3['name'])
            ->connectRooms($room1['name'], 'e', $room4['name'])
            ->connectRooms($room1['name'], 'w', $room5['name'])
            ;
    $map = $mapBuilder->getMap();

    //test map
    $this->assertEquals($room1['name'], $map->getRoom($room1['name'])->name);
    $this->assertEquals($room2['name'], $map->getRoom($room2['name'])->name);
    $this->assertEquals($room3['name'], $map->getRoom($room3['name'])->name);
    $this->assertEquals($room4['name'], $map->getRoom($room4['name'])->name);

    $this->assertEquals($room1['description'], $map->getRoom($room1['name'])->description);
    $this->assertEquals($room2['description'], $map->getRoom($room2['name'])->description);
    $this->assertEquals($room3['description'], $map->getRoom($room3['name'])->description);
    $this->assertEquals($room4['description'], $map->getRoom($room4['name'])->description);

    $this->assertEquals($room1['imageUrl'], $map->getRoom($room1['name'])->imageUrl);
    $this->assertEquals($room2['imageUrl'], $map->getRoom($room2['name'])->imageUrl);
    $this->assertEquals($room3['imageUrl'], $map->getRoom($room3['name'])->imageUrl);
    $this->assertEquals($room4['imageUrl'], $map->getRoom($room4['name'])->imageUrl);

    $this->assertEquals($room2['description'], $map->getRoom($room1['name'])->directions->getDirection('n')->description);
    $this->assertEquals($room3['description'], $map->getRoom($room1['name'])->directions->getDirection('s')->description);
    $this->assertEquals($room4['description'], $map->getRoom($room1['name'])->directions->getDirection('e')->description);
    $this->assertEquals($room5['description'], $map->getRoom($room1['name'])->directions->getDirection('w')->description);

    $this->assertEquals($room1['description'], $map->getRoom($room2['name'])->directions->getDirection('s')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room3['name'])->directions->getDirection('n')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room4['name'])->directions->getDirection('w')->description);
    $this->assertEquals($room1['description'], $map->getRoom($room5['name'])->directions->getDirection('e')->description);

    //test forward direction
    $this->assertEquals($room2['name'], $map->getRoom($room1['name'])->directions->getDirection('n')->nextRoom);
    $this->assertEquals($room3['name'], $map->getRoom($room1['name'])->directions->getDirection('s')->nextRoom);
    $this->assertEquals($room4['name'], $map->getRoom($room1['name'])->directions->getDirection('e')->nextRoom);
    $this->assertEquals($room5['name'], $map->getRoom($room1['name'])->directions->getDirection('w')->nextRoom);

    //test reverse direction
    $this->assertEquals($room1['name'], $map->getRoom($room2['name'])->directions->getDirection('s')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room3['name'])->directions->getDirection('n')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room4['name'])->directions->getDirection('w')->nextRoom);
    $this->assertEquals($room1['name'], $map->getRoom($room5['name'])->directions->getDirection('e')->nextRoom);

    //test spawn location
    $this->assertTrue($map->getRoom($room1['name'])->spawn);
    $this->assertEquals($room1['name'], $map->getSpawnPoint());

    //change spawn location
    $mapBuilder->setSpawnPoint($room2['name']);
    $map = $mapBuilder->getMap();
    $this->assertFalse($map->getRoom($room1['name'])->spawn);
    $this->assertTrue($map->getRoom($room2['name'])->spawn);
    $this->assertEquals($room2['name'], $map->getSpawnPoint());
  }
}
