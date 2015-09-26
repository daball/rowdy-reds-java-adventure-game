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
      ->createRoom('forest')
        ->setRoomDescription('forest',"You are standing in a forest.  There are trees all around you.  A path leads north.\n\nThe obvious exits are: NORTH.")
        ->setRoomImageUrl('forest','null.png')
      ->createRoom('castleEntrance')
        ->setRoomDescription('castleEntrance', "You are at the edge of a forest and are standing at a grand castle.  The castle\'s door lies to the north.\n\nThe obvious exits are: NORTH, SOUTH.")
        ->setRoomImageUrl('castleEntrance', 'castleEntrance.jpg')
      ->createRoom('foyer')
        ->setRoomDescription('foyer', "You are in the castle foyer.\n\nThe obvious exits are: NORTH, EAST, SOUTH, WEST.")
        ->setRoomImageUrl('foyer', 'null.png')
      ->createRoom('tapestryE')
        ->setRoomDescription('tapestryE', "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.\n\nThe obvious exits are: SOUTH, WEST.")
        ->setRoomImageUrl('tapestryE', 'null.png')
      ->createRoom('tapestryW')
        ->setRoomDescription('tapestryW', "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.\n\nThe obvious exits are: NORTH, EAST, SOUTH, WEST.")
        ->setRoomImageUrl('tapestryW', 'null.png')
      ->connectRooms('forest', Direction::$n,'castleEntrance')
      ->connectRooms('castleEntrance', Direction::$n, 'foyer')
      ->connectRooms('foyer', Direction::$n, 'tapestryE')
      ->connectRooms('tapestryE', Direction::$w, 'tapestryW')
//      ->insertDoorObstacle('hall', Direction::$w, 'butlersDoor')
      ->setSpawnPoint('castleEntrance')
      ->getMap();
  }
}
