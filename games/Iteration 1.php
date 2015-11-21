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

GameBuilder::newGame("Iteration 1")
  ->insertRoom((new Room('Forest'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are standing in a forest.  There are trees all around you.  A path leads north.";
    });
    $room->setImageUrl('null.png');
  }))
  ->insertRoom((new Room('Castle Entrance'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.";
    });
    $room->setImageUrl('castleEntrance.png');
  }))
  ->insertRoom((new Room('Foyer'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the castle foyer.";
    });
    $room->setImageUrl('foyer.jpg');
  }))
  ->insertRoom((new Room('Tapestry East'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.";
    });
    $room->setImageUrl('tapestryE.jpg');
  }))
  ->insertRoom((new Room('Tapestry West'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.";
    });
    $room->setImageUrl('tapestryW.jpg');
  }))
  ->insertRoom((new Room('Study'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.";
    });
    $room->setImageUrl('study.jpg');
  }))
  ->insertRoom((new Room('Library'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.";
    });
    $room->setImageUrl('library_key.jpg');
    $container = $room->getComponent('Container');
    $rustyKey = new Key('rustyKey', 'rustySecret');
    $rustyKey->define(function ($rustyKey) {
      $inspector = $rustyKey->getComponent('Inspector');
      $inspector->onInspect(function ($inspector) {
        return "It's a dingy rusty key.";
      });
      $initialOnAssign = $rustyKey->getComponent('Assignable')->onAssign();
      $rustyKey->getComponent('Assignable')->onAssign(function ($assignable, $oldTarget, $newTarget, $index) use ($initialOnAssign) {
        $room = $oldTarget;
        $room->setImageUrl('library.jpg');
        return $initialOnAssign($assignable, $oldTarget, $newTarget, $index);
      });
    });
    $container->insertItem($rustyKey);
  }))
  ->insertRoom((new Room('Conservatory'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.";
    });
    $room->setImageUrl('conservatory.jpg');
  }))
  ->insertRoom((new Room('Lounge'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.";
    });
    $room->setImageUrl('lounge.jpg');
    $room->getComponent('Container')->insertItem(new LockedDoor('door', Direction::$e, new Key('rustyKey', 'rustySecret')));
  }))
  ->insertRoom((new Room("Butlers' Quarters"))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the butler's quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!";
    });
    $room->setImageUrl('butlersQuarters.jpg');
  }))
  ->insertRoom((new Room('Kitchen'))->define(function ($room) {
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
        return "It's a chop of lamb.";
      });
    });
    $container->insertItem($lambChop);
  }))
  ->insertRoom((new Room('Pantry'))->define(function ($room) {
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
  ->insertRoom((new Room('Banquet Hall'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the banquet hall.";
    });
    $room->setImageUrl('banquetHall.jpg');
  }))
  ->insertRoom((new Room('Hallway South'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the south end of a hallway.";
    });
    $room->setImageUrl('null.png');
  }))
  ->insertRoom((new Room('Hallway North'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the north end of a hallway.";
    });
    $room->setImageUrl('null.png');
  }))
  ->insertRoom((new Room("Servants' Quarters"))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.";
    });
    $room->setImageUrl('servantsQuarters.jpg');
    // ->insertObjectInRoom('servantsQuarters', 'footLocker', $footLocker = \playable\FootLocker::create($brassKey)
    //                                                     ->setDescription("It's a servant's simple footLocker chest that is sitting on the floor.")
    //                                                     ->setItem('lamp', \playable\Lamp::create())
    //                     )
  }))
  ->insertRoom((new Room('Taxidermy Room'))->define(function ($room) {
    $stateOfDog = "hungry";
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.";
    });
    $room->setImageUrl('taxidermyRoom_dog.jpg');
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
  ->insertRoom((new Room('Dark Room'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "This room is pitch black.  You can't see anything.";
    });
    $room->setImageUrl('darkRoom.jpg');
  }))

  ->connectRooms('Forest', Direction::$n, 'Castle Entrance')
  ->connectRooms('Castle Entrance', Direction::$n, 'Foyer')
  ->connectRooms('Foyer', Direction::$n, 'Tapestry East')
  ->connectRooms('Tapestry East', Direction::$w, 'Tapestry West')
  ->connectRooms('Tapestry West', Direction::$w, 'Study')
  ->connectRooms('Study', Direction::$s, 'Library')
  ->connectRooms('Foyer', Direction::$e, 'Conservatory')
  ->connectRooms('Conservatory', Direction::$e, 'Lounge')
  ->connectRooms('Lounge', Direction::$e, "Butlers' Quarters")
  ->connectRooms("Butlers' Quarters", Direction::$n, 'Kitchen')
  ->connectRooms('Kitchen', Direction::$n, 'Pantry')
//      ->connectRooms('Kitchen', Direction::$w, 'Banquet Hall')  // remarked out to eliminate the one way door
  ->connectRooms('Banquet Hall', Direction::$s, 'Conservatory')
  ->connectRooms('Banquet Hall', Direction::$n, 'Hallway South')
  ->connectRooms('Hallway South', Direction::$n, 'Hallway North')
  ->connectRooms('Hallway South', Direction::$e, "Servants' Quarters")
  ->connectRooms('Tapestry West', Direction::$n, 'Taxidermy Room')
  ->connectRooms('Taxidermy Room', Direction::$n, 'Dark Room')

  ->setSpawnPoint('Castle Entrance')
;
