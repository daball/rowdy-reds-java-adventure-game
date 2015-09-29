<?php

namespace game;

use map\MapBuilder;
use map\Direction;
use map\Map;

require_once __DIR__.'/../map/MapBuilder.php';
require_once __DIR__.'/../map/Direction.php';
require_once __DIR__.'/../playable/index.php';

///RowdyRedMap builds a map for Iteration 1 using MapBuilder
class RowdyRedMap
{
  public static function buildMap()
  {
    return (new MapBuilder())
      ->createRoom('forest')
        ->setRoomDescription('forest',"You are standing in a forest.  There are trees all around you.  A path leads north.")
        ->setRoomImageUrl('forest','null.png')
      ->createRoom('castleEntrance')
        ->setRoomDescription('castleEntrance', "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.")
        ->setRoomImageUrl('castleEntrance', 'castleEntrance.png')
      ->createRoom('foyer')
        ->setRoomDescription('foyer', "You are in the castle foyer.")
        ->setRoomImageUrl('foyer', 'foyer.jpg')
      ->createRoom('tapestryE')
        ->setRoomDescription('tapestryE', "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.")
        ->setRoomImageUrl('tapestryE', 'tapestryE.jpg')
      ->createRoom('tapestryW')
        ->setRoomDescription('tapestryW', "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.")
        ->setRoomImageUrl('tapestryW', 'tapestryW.jpg')
      ->createRoom('study')
        ->setRoomDescription('study', "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.")
        ->setRoomImageUrl('study', 'study.jpg')
      ->createRoom('library')
        ->setRoomDescription('library', "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.")
        ->setRoomImageUrl('library', 'library_key.jpg')
      ->createRoom('conservatory')
        ->setRoomDescription('conservatory', "You are in a conservatory.")
        ->setRoomImageUrl('conservatory', 'conservatory.jpg')
      ->createRoom('lounge')
        ->setRoomDescription('lounge', "You are in a lounge.  There is a door to the east.")
        ->setRoomImageUrl('lounge', 'lounge.jpg')
      ->createRoom('butlersQuarters')
        ->setRoomDescription('butlersQuarters', "You are in the butler's quarters.")
        ->setRoomImageUrl('butlersQuarters', 'butlersQuarters.jpg')
      ->createRoom('kitchen')
        ->setRoomDescription('kitchen', "You are in the kitchen.")
        ->setRoomImageUrl('kitchen', 'kitchen.jpg')
      ->createRoom('pantry')
        ->setRoomDescription('pantry', "You are in the pantry.")
        ->setRoomImageUrl('pantry', 'pantry_key.jpg')
      ->createRoom('banquetHall')
        ->setRoomDescription('banquetHall', "You are in the banquet hall.")
        ->setRoomImageUrl('banquetHall', 'banquetHall.jpg')
      ->createRoom('hallwayS')
        ->setRoomDescription('hallwayS', "You are in the south end of a hallway.")
        ->setRoomImageUrl('hallwayS', 'null.png')
      ->createRoom('hallwayN')
        ->setRoomDescription('hallwayN', "You are in the north end of a hallway.")
        ->setRoomImageUrl('hallwayN', 'null.png')
      ->createRoom('servantsQuarters')
        ->setRoomDescription('servantsQuarters', "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.")
        ->setRoomImageUrl('servantsQuarters', 'servantsQuarters.jpg')
      ->createRoom('taxidermyRoom')
        ->setRoomDescription('taxidermyRoom', "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.")
        ->setRoomImageUrl('taxidermyRoom', 'taxidermyRoom_dog.jpg')
      ->createRoom('darkRoom')
        ->setRoomDescription('darkRoom', "This room is pitch black.  You can't see anything.")
        ->setRoomImageUrl('darkRoom', 'darkRoom.jpg')

      ->connectRooms('forest', Direction::$n,'castleEntrance')
      ->connectRooms('castleEntrance', Direction::$n, 'foyer')
      ->connectRooms('foyer', Direction::$n, 'tapestryE')
      ->connectRooms('tapestryE', Direction::$w, 'tapestryW')
      ->connectRooms('tapestryW', Direction::$w, 'study')
      ->connectRooms('study', Direction::$s, 'library')
      ->connectRooms('foyer', Direction::$e, 'conservatory')
      ->connectRooms('conservatory', Direction::$e, 'lounge')
      ->connectRooms('lounge', Direction::$e, 'butlersQuarters')
      ->connectRooms('butlersQuarters', Direction::$n, 'kitchen')
      ->connectRooms('kitchen', Direction::$n, 'pantry')
//      ->connectRooms('kitchen', Direction::$w, 'banquetHall')  // remarked out to eliminate the one way door
      ->connectRooms('banquetHall', Direction::$s, 'conservatory')
      ->connectRooms('banquetHall', Direction::$n, 'hallwayS')
      ->connectRooms('hallwayS', Direction::$n, 'hallwayN')
      ->connectRooms('hallwayS', Direction::$e, 'servantsQuarters')
      ->connectRooms('tapestryW', Direction::$n, 'taxidermyRoom')
      ->connectRooms('taxidermyRoom', Direction::$n, 'darkRoom')

      ->insertObjectInRoom('library', 'rustyKey', $rustyKey = \playable\Key::create('rustyKey')
                                                          ->setDescription("It's a dingy rusty key.")
                          )
      ->insertObjectInRoom('pantry', 'brassKey', $brassKey = \playable\Key::create('brassKey')
                                                          ->setDescription("It's a nice and shiny brass key.")
                          )
      ->insertObjectInRoom('kitchen', 'lambChop', \playable\LambChop::create())
      ->insertObstacleObjectInRoom('taxidermyRoom', Direction::$n, 'dog', $dog = \playable\Dog::create()
                                                          ->onInspect(function () {
                                                            if ($this->hungry) {
                                                              return "It's a sizeable looking dog is sitting by the northern door, watching you alertly.";
                                                            }
                                                            else {
                                                              return "The dog growls at you menacingly, and will not let you pass by.";
                                                            }
                                                          })
                          )
      ->insertObjectInRoom('kitchen', 'dogBowl', \playable\DogBowl::create($dog))

      ->insertObjectInRoom('taxidermyRoom','bowl', \playable\Container::create('bowl')
                                                          ->setDescription("It's an empty bowl sitting on the floor.")
                          )
      ->insertObjectInRoom('servantsQuarters', 'footLocker', $footLocker = \playable\FootLocker::create($brassKey)
                                                          ->setDescription("It's a servant's simple footLocker chest that is sitting on the floor.")
                                                          ->setItem('lamp', \playable\Lamp::create())
                          )
      ->insertObstacleObjectInRoom('lounge', Direction::$e, 'door', \playable\LockedDoor::create($rustyKey)
                                                          ->setDescription("The door seems to be locked.")
                                  )
      ->insertObstacleObjectInRoom('kitchen', Direction::$w, 'door', \playable\Door::create())

      ->setSpawnPoint('castleEntrance')
      ->getMap();
  }
}
