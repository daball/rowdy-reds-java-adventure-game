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

function constructBasicRoom($n, $d, $i) {
  return \game\assembleRoom(array('name'=>$n, 'description'=>$d, 'imageUrl'=>$i));
}

/* SIMPLE ROOM DEFINITIONS */
$gameName = pathinfo(__FILE__)['filename'];
$forest = array(
  'name'        => "Forest",
  'description' => "You are standing in a forest.  There are trees all around you.  A path leads north.",
  'imageUrl'    => "forest.png",
);
$castleEntrance = array(
  'name'        => "Castle Entrance",
  'description' => "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.",
  'imageUrl'    => "castleEntrance.png",
);
$foyer = array(
  'name'        => "Foyer",
  'description' => "You are in a foyer. Intricate stonework decorates the foyer and lamps line the wall.",
  'imageUrl'    => "foyer.jpg",
);
$tapestryE = array(
  'name'        => "Eastern End of Tapestry Room",
  'description' => "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.",
  'imageUrl'    => "tapestryE.jpg",
);
$tapestryW = array(
  'name'        => "Western End of Tapestry Room",
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
$backHallway = array(
  'name'        => "Back Hallway",
  'description' => "You are in a hallway.",
  'imageUrl'    => "halllway1.jpg",
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
$chessRoom = array(
  'name'        => "Chess Room",
  'description' => "This room is pitch black.  You can't see anything.",
  'imageUrl'    => "darkRoom.jpg",
);

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
  ->insertRoom(\game\assembleRoom($backHallway))
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
  ->insertRoom(\game\assembleRoom($chessRoom))
  ->insertRoom(constructBasicRoom('Vestibule', 'You are in a small vestibule.', 'vestibule.jpg'))
  ->insertRoom(constructBasicRoom('Art Gallery', 'You are in the castle art gallery.', 'artGallery.jpg'))
  ->insertRoom(constructBasicRoom('Base of Western Tower', 'You are in a circular room with a spiral staircase leading up to the right.', 'westTower1.jpg'))
  ->insertRoom(constructBasicRoom('Grand Hall', 'You are in the Grand Hall.', 'grandHall.jpg'))
  ->insertRoom(constructBasicRoom('Grand Staircase', 'You are at a magnificant staircase at the north end of the Grand Hall.', 'grandStaircase.jpg'))
  ->insertRoom(constructBasicRoom('Base of Eastern Tower', 'You are in a circular room with a spiral staircase leading up to the left.', 'eastTower1.jpg'))
  ->insertRoom(constructBasicRoom('Courtyard', 'You are in the castle courtyard.', 'courtyard.jpg'))
  ->insertRoom(constructBasicRoom('Stable', 'You are in a stable.', 'stables.jpg'))
  ->insertRoom(constructBasicRoom('Smithery', 'You are in a smithery.', 'smithery.jpg'))

  // Upper Floor
  ->insertRoom(constructBasicRoom('Grand Balcony', 'You are on a grand balcony that is overlooking the Grand Hall below.', 'grandBalcony.jpg'))
  ->insertRoom(constructBasicRoom('Billiards Room', 'You are in a billiards room.', 'billiardsRoom.jpg'))
  ->insertRoom(constructBasicRoom('Map Room', 'You are in a strange room with several globes.  The walls are all covered with maps.', 'mapRoom.jpg'))
  ->insertRoom(constructBasicRoom('Drawing Room', 'You are in a room with several musical instruments, an easel, some jars of paint, a tilted table, and various drawing utensils.', 'drawingRoom.jpg'))
  ->insertRoom(constructBasicRoom('Observatory', 'You are in a run down obervatory.  The walls are peeling, and old drapes cover tall floor to ceiling windows.  An old telescope sits on the floor.', 'observatory.jpg'))
  ->insertRoom(constructBasicRoom('Master Bedchambers', 'You are in a lavishly decorated bedroom.  A four poster bed covered with crushed velvet blankets and plush pillows sit toward the middle of the room.', 'masterBedchambers.jpg'))
  ->insertRoom(constructBasicRoom('Bathroom', 'You are in a bathroom that is off the master bed chambers.', 'bathroom.jpg'))
  ->insertRoom(constructBasicRoom('North End of Corridor', 'You are in the North End of the corridor.', 'corridor2fn.jpg'))
  ->insertRoom(constructBasicRoom('South End of Corridor', 'You are in the South End of the corridor.', 'corridor2fs.jpg'))
  ->insertRoom(constructBasicRoom('Odd Bedroom', 'You are in a bedroom with three beds.  A fire crackles in the fireplace, making the room soft, warm, and comfortable.', 'oddBedroom.jpg'))
  ->insertRoom(constructBasicRoom('Top of Western Tower', 'You are in the top of a tower.', 'westTowerTop.jpg'))
  ->insertRoom(constructBasicRoom('Top of Eastern Tower', 'You are in the top of a tower.', 'eastTowerTop.jpg'))
//  ->insertRoom(constructBasicRoom('', '', ''))


/*  ->insertRoom((new Room('Chess Room'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "This room is pitch black.  You can't see anything.";
    });
    $room->setImageUrl('darkRoom.jpg');
  }))
  ->insertRoom((new Room('Vestibule'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a small vestibule.";
    });
    $room->setImageUrl('vestibule.jpg');
  }))
  ->insertRoom((new Room('Art Gallery'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the castle art gallery.";
    });
    $room->setImageUrl('artGallery.jpg');
  }))*/

  // Iteration 1 Room Connections
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
  ->connectRooms($kitchen,          Direction::$w,    $banquetHall)
  ->connectRooms($banquetHall,      Direction::$s,    $conservatory)
  ->connectRooms($banquetHall,      Direction::$n,    $backHallway)
  ->connectRooms($backHallway,      Direction::$e,    $servantsQuarters)
  ->connectRooms($tapestryW,        Direction::$n,    $taxidermyRoom)
  ->connectRooms($taxidermyRoom,    Direction::$n,    $chessRoom)

  // Iteration 2 Room Connections
  ->connectRooms($foyer,            Direction::$w,    'Vestibule')
  ->connectRooms('Vestibule',       Direction::$w,    'Base of Western Tower')
  ->connectRooms('Base of Western Tower', Direction::$u, 'Top of Western Tower')
  ->connectRooms($study,            Direction::$n,    'Art Gallery')
  ->connectRooms($kitchen,          Direction::$e,    'Courtyard')
  ->connectRooms('Courtyard', Direction::$e, 'Stable')
  ->connectRooms('Stable', Direction::$s, 'Smithery')
  ->connectRooms($banquetHall, Direction::$w, 'Grand Hall')
  ->connectRooms('Grand Hall', Direction::$s, 'Eastern End of Tapestry Room')
  ->connectRooms('Grand Hall', Direction::$n, 'Grand Staircase')
  ->connectRooms('Grand Staircase', Direction::$u, 'Grand Balcony')
  ->connectRooms("Servants' Quarters", Direction::$n, 'Base of Eastern Tower')
  ->connectRooms('Base of Eastern Tower', Direction::$u, 'Top of Eastern Tower')

  // Second Floor:
  ->connectRooms('Grand Balcony', Direction::$e, 'Drawing Room')
  ->connectRooms('Grand Balcony', Direction::$w, 'Observatory')
  ->connectRooms('Observatory', Direction::$s, 'Map Room')
  ->connectRooms('Grand Balcony', Direction::$s, 'North End of Corridor')
  ->connectRooms('North End of Corridor', Direction::$s, 'South End of Corridor')
  ->connectRooms('North End of Corridor', Direction::$w, 'Map Room')
  ->connectRooms('North End of Corridor', Direction::$e, 'Billiards Room')
  ->connectRooms('South End of Corridor', Direction::$w, 'Odd Bedroom')
  ->connectRooms('South End of Corridor', Direction::$e, 'Master Bedchambers')
  ->connectRooms('Master Bedchambers', Direction::$n, 'Bathroom')
  /*->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  ->connectRooms('', Direction::$, '')
  */

  ->setSpawnPoint('Castle Entrance')
;
