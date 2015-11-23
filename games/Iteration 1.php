<?php

namespace games;

require_once __DIR__.'/../app/game/GameBuilder.php';
require_once __DIR__.'/../app/game/Direction.php';
require_once __DIR__.'/../app/playable/index.php';
require_once __DIR__.'/../app/components/index.php';

use \game\GameBuilder;
use \game\Direction;
use \game\Map;
use \game\Room;
use \playable\BasicContainer;
use \playable\Key;
use \playable\Food;
use \playable\LockedDoor;
use \playable\Dog;

/* SIMPLE ROOM DEFINITIONS */
$gameName = pathinfo(__FILE__)['filename'];
$forest = array(
  'name'        => "Forest",
  'description' => "You are standing in a forest.  There are trees all around you.  A path leads north.",
  'imageUrl'    => "null.png",
);
$castleEntrance = array(
  'name'        => "Castle Entrance",
  'description' => "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.",
  'imageUrl'    => "castleEntrance.png",
);
$foyer = array(
  'name'        => "Foyer",
  'description' => "You are in the castle foyer.",
  'imageUrl'    => "foyer.jpg",
);
$tapestryE = array(
  'name'        => "Tapestry East",
  'description' => "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.",
  'imageUrl'    => "tapestryE.jpg",
);
$tapestryW = array(
  'name'        => "Tapestry West",
  'description' => "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.",
  'imageUrl'    => "tapestryW.jpg",
);
$study = array(
  'name'        => "Study",
  'description' => "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.",
  'imageUrl'    => "study.jpg",
  'items'       => array(
    'note'       => array(
      'type'                    => 'note',
      'name'                    => "note",
      'description'             => "I'm a note!",
    ),
  ),
);
$library = array(
  'name'        => "Library",
  'description' => "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.",
  'imageUrl'    => "library_key.jpg",
  'items'       => array(
    'rustyKey'    => array(
      'type'                    => 'key',
      'name'                    => "rustyKey",
      'secret'                  => "rustySecret",
      'description'             => "It's a dingy rusty key.",
      'onAssign.room.imageUrl'  => "library.jpg",
    ),
  ),
);
$conservatory = array(
  'name'        => "Conservatory",
  'description' => "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.",
  'imageUrl'    => "conservatory.jpg",
);
$lounge = array(
  'name'        => "Lounge",
  'description' => "You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.",
  'imageUrl'    => "lounge.jpg",
  'items'       => array(
    'lockedDoor'  => array(
      'type'        => "lockedDoor",
      'name'        => "door",
      'direction'   => Direction::$e,
      'secret'  => $library['items']['rustyKey']['secret'],
    ),
  ),
);
$butlersQuarters = array(
  'name'        => "Butlers' Quarters",
  'description' => "You are in the butler's quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!",
  'imageUrl'    => "butlersQuarters.jpg",
);
$kitchen = array(
  'name'        => "Kitchen",
  'description' => "You are in the kitchen.  The smell of freshly cooked meat still lingers heavily in the air.",
  'imageUrl'    => "kitchen.jpg",
  'items'       => array(
    //FYI: There is no room to the WEST of Kitchen
    'door'        => array(
      'type'        => "door",
      'name'        => "door",
      'direction'   => Direction::$w,
    ),
    'lambChop'  => array(
      'type'        => "food",
      'name'        => "lambChop",
      'description' => "It's a chop of lamb."
    ),
  ),
);
$pantry = array(
  'name'        => "Pantry",
  'description' => "You descend down some stairs into in the kitchen pantry.  The pantry is stocked with many dry goods.",
  'imageUrl'    => "pantry_key.jpg",
  'items'       => array(
    'brassKey'    => array(
      'type'                    => 'key',
      'name'                    => "brassKey",
      'secret'                  => "brassSecret",
      'description'             => "It's a nice and shiny brass key.",
      'onAssign.room.imageUrl'  => "pantry.jpg",
    ),
  ),
);
$banquetHall = array(
  'name'        => "Banquet Hall",
  'description' => "You are in the banquet hall.",
  'imageUrl'    => "banquetHall.jpg",
);
$hallwayS = array(
  'name'        => "Hallway South",
  'description' => "You are in the south end of a hallway.",
  'imageUrl'    => "null.jpg",
);
$hallwayN = array(
  'name'        => "Hallway North",
  'description' => "You are in the north end of a hallway.",
  'imageUrl'    => "null.jpg",
);
$servantsQuarters = array(
  'name'        => "Servants' Quarters",
  'description' => "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.",
  'imageUrl'    => "servantsQuarters.jpg",
  // ->insertObjectInRoom('servantsQuarters', 'footLocker', $footLocker = \playable\FootLocker::create($brassKey)
  //                                                     ->setDescription("It's a servant's simple footLocker chest that is sitting on the floor.")
  //                                                     ->setItem('lamp', \playable\Lamp::create())
  //                     )
);
$taxidermyRoom = array(
  'name'        => "Taxidermy Room",
  'description' => "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.",
  'imageUrl'    => "taxidermyRoom_dog.jpg",
);
$darkRoom = array(
  'name'        => "Dark Room",
  'description' => "This room is pitch black.  You can't see anything.",
  'imageUrl'    => "darkRoom.jpg",
);

/* BUILD GAME */
GameBuilder::newGame($gameName)
  ->insertRoom(\game\assembleRoom($forest))
  ->insertRoom(\game\assembleRoom($castleEntrance))
  ->insertRoom(\game\assembleRoom($foyer))
  ->insertRoom(\game\assembleRoom($tapestryE))
  ->insertRoom(\game\assembleRoom($tapestryW))
  ->insertRoom(\game\assembleRoom($study))
  ->insertRoom(\game\assembleRoom($library))
  ->insertRoom(\game\assembleRoom($conservatory))
  ->insertRoom(\game\assembleRoom($lounge))
  ->insertRoom(\game\assembleRoom($butlersQuarters))
  ->insertRoom(\game\assembleRoom($kitchen))
  ->insertRoom(\game\assembleRoom($pantry))
  ->insertRoom(\game\assembleRoom($banquetHall))
  ->insertRoom(\game\assembleRoom($hallwayS))
  ->insertRoom(\game\assembleRoom($hallwayN))
  ->insertRoom(\game\assembleRoom($servantsQuarters)->define(function ($room) use ($servantsQuarters) {
    // ->insertObjectInRoom('servantsQuarters', 'footLocker', $footLocker = \playable\FootLocker::create($brassKey)
    //                                                     ->setDescription("It's a servant's simple footLocker chest that is sitting on the floor.")
    //                                                     ->setItem('lamp', \playable\Lamp::create())
    //                     )
  }))
  ->insertRoom(\game\assembleRoom($taxidermyRoom)->define(function ($room) use ($taxidermyRoom) {
    $stateOfDog = "hungry";
    $room->getComponent('Container')->insertItem((new Dog('dog', Direction::$n))->define(function ($dog) use ($stateOfDog) {
      $foodConsumer = $dog->getComponent('FoodConsumer');
      $initialOnEat = $foodConsumer->onEat();
      $foodConsumer->onEat(function ($foodConsumer) use ($stateOfDog, $initialOnEat) {
        $dog = $foodConsumer->getParent();
        $room = $dog->getContainer();
        $room->setImageUrl('taxidermyRoom.jpg');
        $stateOfDog = "eating";
        return $initialOnEat($foodConsumer);
      });
      $inspector = $dog->getComponent('Inspector');
      $initialOnInspect = $inspector->onInspect();
      $inspector->onInspect(function ($inspector) use ($stateOfDog, $initialOnInspect) {
        switch ($stateOfDog) {
          case "hungry":
            return "It's a sizeable looking dog is sitting by the northern door, watching you alertly.";
          case "happy":
            return "The dog is now satisfied from eating and smiles at you.  You are a new best friend.";
          default:
            return $initialOnInspect($inspector);
        }
      });
    }));
    $room->getComponent('Container')->insertItem((new BasicContainer('bowl'))->define(function ($bowl) use ($stateOfDog) {
      $bowl->getComponent('Inspector')->onInspect(function ($inspector) use (&$stateOfDog) {
        $bowl = $inspector->getParent();
        $room = $bowl->getContainer();
        $dog = $room->getComponent('Container')->findItemByName('dog');
        $foodConsumer = $dog->getComponent('FoodConsumer');
        if ($stateOfDog == "eating") {
          $stateOfDog = "happy";
          return "The dog is licking the bowl clean.";
        }
        return "It's an empty bowl sitting on the floor.";
      });
      $bowl->getComponent('Container')->setValidItemTypes(array('\playable\Food'));
      $bowl->getComponent('Container')->onSet(function () use (&$stateOfDog) {
        $stateOfDog = "eating";
        return "The dog runs over and starts eating from the bowl.";
      });
    }));
  }))
  ->insertRoom(\game\assembleRoom($darkRoom))

  ->connectRooms($forest,           Direction::$n,    $castleEntrance)
  ->connectRooms($castleEntrance,   Direction::$n,    $foyer)
  ->connectRooms($foyer,            Direction::$n,    $tapestryE)
  ->connectRooms($tapestryE,        Direction::$w,    $tapestryW)
  ->connectRooms($tapestryW,        Direction::$w,    $study)
  ->connectRooms($study,            Direction::$s,    $library)
  ->connectRooms($foyer,            Direction::$e,    $conservatory)
  ->connectRooms($conservatory,     Direction::$e,    $lounge)
  ->connectRooms($lounge,           Direction::$e,    $butlersQuarters)
  ->connectRooms($butlersQuarters,  Direction::$n,    $kitchen)
  ->connectRooms($kitchen,          Direction::$n,    $pantry)
  // ->connectRooms($kitchen,          Direction::$w,    $banquetHall)  // remarked out to eliminate the one way door
  ->connectRooms($banquetHall,      Direction::$s,    $conservatory)
  ->connectRooms($banquetHall,      Direction::$n,    $hallwayS)
  ->connectRooms($hallwayS,         Direction::$n,    $hallwayN)
  ->connectRooms($hallwayS,         Direction::$e,    $servantsQuarters)
  ->connectRooms($tapestryW,        Direction::$n,    $taxidermyRoom)
  ->connectRooms($taxidermyRoom,    Direction::$n,    $darkRoom)

  ->setSpawnPoint($castleEntrance)
;
