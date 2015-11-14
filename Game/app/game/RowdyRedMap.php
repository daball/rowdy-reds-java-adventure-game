<?php

namespace game;

require_once __DIR__.'/../map/MapBuilder.php';
require_once __DIR__.'/../map/Direction.php';
require_once __DIR__.'/../playable/index.php';
require_once __DIR__.'/../components/index.php';

use map\MapBuilder;
use map\Direction;
use map\Map;
use map\Room;
use playable\BasicContainer;
use playable\Key;
use playable\Food;

///RowdyRedMap builds a map for Iteration 1 using MapBuilder
class RowdyRedMap
{
  public static function buildMap()
  {
    return (new MapBuilder())
      ->insertRoom((new Room('forest'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are standing in a forest.  There are trees all around you.  A path leads north.";
        });
        $room->setImageUrl('null.png');
      }))
      ->insertRoom((new Room('castleEntrance'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.";
        });
        $room->setImageUrl('castleEntrance.png');
      }))
      ->insertRoom((new Room('foyer'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the castle foyer.";
        });
        $room->setImageUrl('foyer.jpg');
      }))
      ->insertRoom((new Room('tapestryE'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.";
        });
        $room->setImageUrl('tapestryE.jpg');
      }))
      ->insertRoom((new Room('tapestryW'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.";
        });
        $room->setImageUrl('tapestryW.jpg');
      }))
      ->insertRoom((new Room('study'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.";
        });
        $room->setImageUrl('study.jpg');
      }))
      ->insertRoom((new Room('library'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.";
        });
        $room->setImageUrl('library_key.jpg');
        $container = $room->getComponent('Container');
        $rustyKey = new Key('rustyKey', 'rustyKeySecret');
        $rustyKey->define(function ($rustyKey) {
          $inspector = $rustyKey->getComponent('Inspector');
          $inspector->onInspect(function ($inspector) {
            return "It's a dingy rusty key.";
          });
        });
        $container->insertItem($rustyKey);
      }))
      ->insertRoom((new Room('conservatory'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.";
        });
        $room->setImageUrl('conservatory.jpg');
      }))
      ->insertRoom((new Room('lounge'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.";
        });
        $room->setImageUrl('lounge.jpg');
        // ->insertObstacleObjectInRoom('lounge', Direction::$e, 'door', \playable\LockedDoor::create($rustyKey)
        //                                                     ->setDescription("The door seems to be locked.")
        //                             )
      }))
      ->insertRoom((new Room('butlersQuarters'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the butler's quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!";
        });
        $room->setImageUrl('butlersQuarters.jpg');
      }))
      ->insertRoom((new Room('kitchen'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the kitchen.  The smell of freshly cooked meat still lingers heavily in the air.";
        });
        $room->setImageUrl('kitchen.jpg');
        $container = $room->getComponent('Container');

        // ->insertObstacleObjectInRoom('kitchen', Direction::$w, 'door', \playable\Door::create())

        $lambChop = new Food("lambChop");
        $lambChop->define(function ($lambChop) {
          $inspector = $lambChop->getComponent('Inspector');
          $inspector->onInspect(function ($inspector) {
            return "It's a nice and shiny brass key.";
          });
        });
        $container->insertItem($lambChop);

        $dogBowl = new BasicContainer('dogBowl');
        $dogBowl->define(function ($dogBowl) {

        });
        //->insertObjectInRoom('kitchen', 'dogBowl', \playable\DogBowl::create($dog))
      }))
      ->insertRoom((new Room('pantry'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You descend down some stairs into in the kitchen pantry.  The pantry is stocked with many dry goods.";
        });
        $room->setImageUrl('pantry_key.jpg');

        $container = $room->getComponent('Container');
        $brassKey = new Key('brassKey', 'brassKeySecret');
        $brassKey->define(function ($brassKey) {
          $inspector = $brassKey->getComponent('Inspector');
          $inspector->onInspect(function ($inspector) {
            return "It's a nice and shiny brass key.";
          });
        });
        $container->insertItem($brassKey);
      }))
      ->insertRoom((new Room('banquetHall'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the banquet hall.";
        });
        $room->setImageUrl('banquetHall.jpg');
      }))
      ->insertRoom((new Room('hallwayS'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the south end of a hallway.";
        });
        $room->setImageUrl('null.png');
      }))
      ->insertRoom((new Room('hallwayN'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in the north end of a hallway.";
        });
        $room->setImageUrl('null.png');
      }))
      ->insertRoom((new Room('servantsQuarters'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.";
        });
        $room->setImageUrl('servantsQuarters.jpg');;
        // ->insertObjectInRoom('servantsQuarters', 'footLocker', $footLocker = \playable\FootLocker::create($brassKey)
        //                                                     ->setDescription("It's a servant's simple footLocker chest that is sitting on the floor.")
        //                                                     ->setItem('lamp', \playable\Lamp::create())
        //                     )
      }))
      ->insertRoom((new Room('taxidermyRoom'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.";
        });
        $room->setImageUrl('taxidermyRoom_dog.jpg');
        // ->insertObstacleObjectInRoom('taxidermyRoom', Direction::$n, 'dog', $dog = \playable\Dog::create()
        //                                                     ->onInspect(function () {
        //                                                       if ($this->hungry) {
        //                                                         return "It's a sizeable looking dog is sitting by the northern door, watching you alertly.";
        //                                                       }
        //                                                       else {
        //                                                         return "The dog growls at you menacingly, and will not let you pass by.";
        //                                                       }
        //                                                     }))
        // ->insertObjectInRoom('taxidermyRoom','bowl', \playable\Container::create('bowl')
        //                                                     ->setDescription("It's an empty bowl sitting on the floor.")
        //                     )
      }))
      ->insertRoom((new Room('darkRoom'))->define(function ($room) {
        $room->getComponent('Inspector')->onInspect(function ($inspector) {
          return "This room is pitch black.  You can't see anything.";
        });
        $room->setImageUrl('darkRoom.jpg');
      }))

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

      ->setSpawnPoint('castleEntrance')

      ->getMap();
  }
}
