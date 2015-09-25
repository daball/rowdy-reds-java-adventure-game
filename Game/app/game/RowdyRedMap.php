<?php

namespace game;
use map\MapBuilder;
use map\Direction;
use map\Map;


require_once __DIR__.'/../map/MapBuilder.php';
require_once __DIR__.'/../map/Direction.php';

///SampleMap builds a sample map for Iteration 1 using MapBuilder
class RowdyRedMap
{
  public static function buildMap()
  {
    return (new MapBuilder())
      ->createRoom('entrance')
        ->setRoomDescription('entrance', 'You are standing at a castle door. A door lies to the north.')
        ->setRoomImageUrl('entrance', 'background.jpg')
      ->createRoom('hall')
        ->setRoomDescription('hall', 'You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.')
        ->setRoomImageUrl('hall', 'mainHall.jpg')
      ->createRoom('kitchen')
        ->setRoomDescription('kitchen', 'You are in a kitchen. Someone has been cooking here lately and the smell of mutton still hangs heavy in the air. The hallway lies to the east.')
        ->setRoomImageUrl('kitchen', 'CastleRoom.jpg')
      ->connectRooms('entrance', Direction::$n, 'hall')
      ->connectRooms('hall', Direction::$w, 'kitchen')
      ->insertDoorObstacle('hall', Direction::$w, 'door')
      ->setSpawnPoint('entrance')
      ->getMap();
  }
}
