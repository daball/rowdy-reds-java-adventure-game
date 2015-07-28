<?php

require_once 'MapBuilder.php';
require_once 'Direction.php';

///SampleMap builds a sample map for Iteration 1 using MapBuilder
class SampleMap
{
  public static function buildSampleMap()
  {
    return (new MapBuilder())
      ->createRoom('entrance')
        ->setRoomDescription('entrance', 'You are standing at a castle door. A door lies to the north.')
        ->setRoomImageUrl('entrance', 'background.jpg')
      ->createRoom('hall')
        ->setRoomDescription('hall', 'You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.')
        ->setRoomImageUrl('hall', 'mainHall.jpg')
      ->createRoom('kitchen')
        ->setRoomDescription('kitchen', 'You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.')
        ->setRoomImageUrl('kitchen', 'castleRoom.jpg')
      ->connectRooms('entrance', Direction::$n, 'hall')
      ->connectRooms('hall', Direction::$w, 'kitchen')
      ->setSpawnPoint('entrance')
      ->getMap();
  }
}
