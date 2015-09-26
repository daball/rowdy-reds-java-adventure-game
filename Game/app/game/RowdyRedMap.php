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
        ->setRoomDescription('castleEntrance', "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.\n\nThe obvious exits are: NORTH, SOUTH.")
        ->setRoomImageUrl('castleEntrance', 'castleEntrance.jpg')
      ->createRoom('foyer')
        ->setRoomDescription('foyer', "You are in the castle foyer.\n\nThe obvious exits are: NORTH, EAST, SOUTH.")
        ->setRoomImageUrl('foyer', 'null.png')
      ->createRoom('tapestryE')
        ->setRoomDescription('tapestryE', "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.\n\nThe obvious exits are: SOUTH, WEST.")
        ->setRoomImageUrl('tapestryE', 'null.png')
      ->createRoom('tapestryW')
        ->setRoomDescription('tapestryW', "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.\n\nThe obvious exits are: NORTH, EAST, SOUTH, WEST.")
        ->setRoomImageUrl('tapestryW', 'null.png')
      ->createRoom('study')
        ->setRoomDescription('study', "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.\n\nThe obvious exits are: NORTH, EAST, SOUTH.")
        ->setRoomImageUrl('study', 'study.jpg')
      ->createRoom('library')
        ->setRoomDescription('library', "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.\n\nThe obvious exits are: NORTH.")
        ->setRoomImageUrl('library', 'library.jpg')
      ->createRoom('conservatory')
        ->setRoomDescription('conservatory', "You are in a conservatory.\n\nThe obvious exits are: EAST, WEST.")
        ->setRoomImageUrl('conservatory', 'null.png')
      ->createRoom('lounge')
        ->setRoomDescription('lounge', "You are in a lounge.  There is a door to the east.\n\nThe obvious exits are: EAST, WEST.")
        ->setRoomImageUrl('lounge', 'null.png')
      ->createRoom('butlersQuarters')
        ->setRoomDescription('butlersQuarters', "You are in the butler's quarters.\n\nThe obvious exits are: NORTH, WEST.")
        ->setRoomImageUrl('butlersQuarters', 'null.png')
        
      ->connectRooms('forest', Direction::$n,'castleEntrance')
      ->connectRooms('castleEntrance', Direction::$n, 'foyer')
      ->connectRooms('foyer', Direction::$n, 'tapestryE')
      ->connectRooms('tapestryE', Direction::$w, 'tapestryW')
      ->connectRooms('tapestryW', Direction::$w, 'study')
      ->connectRooms('study', Direction::$s, 'library')
      ->connectRooms('foyer', Direction::$e, 'conservatory')
      ->connectRooms('conservatory', Direction::$e, 'lounge')
      ->connectRooms('lounge', Direction::$e, 'butlersQuarters')
      ->insertDoorObstacle('lounge', Direction::$e, 'door')
      ->setSpawnPoint('castleEntrance')
      ->getMap();
  }
}
