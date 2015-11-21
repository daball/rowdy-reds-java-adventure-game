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

function constructBasicRoom($name, $desc, $image) {

  return (new Room($name))->define(function ($room) use ($desc, $image) {
      $room->getComponent('Inspector')->onInspect(function ($inspector) use ($desc) {
        return $desc;
      });
      $room->setImageUrl($image);
    });
}

GameBuilder::newGame("Iteration 3")
  ->insertRoom((new Room('Forest'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are standing in a forest.  There are trees all around you.  A path leads north.";
    });
    $room->setImageUrl('forest.png');
  }))
  ->insertRoom((new Room('Castle Entrance'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.";
    });
    $room->setImageUrl('castleEntrance.png');
  }))
  ->insertRoom((new Room('Foyer'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a foyer. Intricate stonework decorates the foyer and lamps line the wall.";
    });
    $room->setImageUrl('foyer.jpg');
  }))
  ->insertRoom((new Room('Eastern End of Tapestry Room'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.";
    });
    $room->setImageUrl('tapestryE.jpg');
  }))
  ->insertRoom((new Room('Western End of Tapestry Room'))->define(function ($room) {
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
  ->insertRoom((new Room('Back Hallway'))->define(function ($room) {
    $room->getComponent('Inspector')->onInspect(function ($inspector) {
      return "You are in a hallway.";
    });
    $room->setImageUrl('hallway1.jpg');
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
    $room->getComponent('Container')->insertItem((new BasicContainer('bowl'))->define(function ($bowl) use (&$stateOfDog) {
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
  ->insertRoom(constructBasicRoom('Chess Room', "This room is pitch black.  You can't see anything.", 'darkRoom.jpg'))
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
  
  // Iteration 3 Main Floor Rooms:
  ->insertRoom(constructBasicRoom('Infirmary', 'You are in an infirmary.', 'infirmary.jpg'))
  ->insertRoom(constructBasicRoom('Pantry Storage Room', 'You are in what appears to be a storage room.', 'storageRoom.jpg'))
  ->insertRoom(constructBasicRoom('Cloak Room', 'You are in a small alcove for storing cloaks, gloves, and the like.', 'cloakRoom.jpg'))
  
  // Iteration 3 Lower Level Rooms:
  ->insertRoom(constructBasicRoom('Alcove', 'You are in a small alcove.  There is a large door to the south.', 'alcove.jpg'))
  ->insertRoom(constructBasicRoom('Treasury Room', 'You are in a massive treasury room, and in front of you, guarding the treasure hoard, is a huge red dragon!', 'treasury.jpg'))
  ->insertRoom(constructBasicRoom('Hall of Mirrors', 'You are in a hall of mirrors.', 'hallOfMirrors.jpg'))
  ->insertRoom(constructBasicRoom('Rack Room', "You are in a rack room.  There are various devices of torture all around.  You really don't like being here.", 'rackRoom.jpg'))
  ->insertRoom(constructBasicRoom('Boiler Room', 'You are in a boiler boom.', 'boilerRoom.jpg'))
  ->insertRoom(constructBasicRoom('Portcullis', 'You are in a room with a portcullis on the southern side.  You also see a winch on the wall.', 'portcullis.jpg'))
  ->insertRoom(constructBasicRoom('Armory', 'You are in an armory.', 'armory.jpg'))
  ->insertRoom(constructBasicRoom('Wine Cellar', 'You are in a wine cellar.', 'wineCellar.jpg'))
  ->insertRoom(constructBasicRoom('Cellar Storage Room', 'You are in a cellar storage room of sorts.', 'storageRoom2.jpg'))
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

  // Room Connections
  // Iteration 1 Main Floor:
  ->connectRooms('Forest', Direction::$n, 'Castle Entrance')
  ->connectRooms('Castle Entrance', Direction::$n, 'Foyer')
  ->connectRooms('Foyer', Direction::$n, 'Eastern End of Tapestry Room')
  ->connectRooms('Eastern End of Tapestry Room', Direction::$w, 'Western End of Tapestry Room')
  ->connectRooms('Western End of Tapestry Room', Direction::$w, 'Study')
  ->connectRooms('Study', Direction::$s, 'Library')
  ->connectRooms('Foyer', Direction::$e, 'Conservatory')
  ->connectRooms('Conservatory', Direction::$e, 'Lounge')
  ->connectRooms('Lounge', Direction::$e, "Butlers' Quarters")
  ->connectRooms("Butlers' Quarters", Direction::$n, 'Kitchen')
  ->connectRooms('Kitchen', Direction::$n, 'Pantry')
  ->connectRooms('Kitchen', Direction::$w, 'Banquet Hall')
  ->connectRooms('Banquet Hall', Direction::$s, 'Conservatory')
  ->connectRooms('Banquet Hall', Direction::$n, 'Back Hallway')
  ->connectRooms('Back Hallway', Direction::$e, "Servants' Quarters")
  ->connectRooms('Western End of Tapestry Room', Direction::$n, 'Taxidermy Room')
  ->connectRooms('Taxidermy Room', Direction::$n, 'Chess Room')

  // Iteration 2 Main Floor:
  ->connectRooms('Foyer', Direction::$w, 'Vestibule')
  ->connectRooms('Vestibule', Direction::$w, 'Base of Western Tower')
  ->connectRooms('Base of Western Tower', Direction::$u, 'Top of Western Tower')
  ->connectRooms('Study', Direction::$n, 'Art Gallery')
  ->connectRooms('Kitchen', Direction::$e, 'Courtyard')
  ->connectRooms('Courtyard', Direction::$e, 'Stable')
  ->connectRooms('Stable', Direction::$s, 'Smithery')
  ->connectRooms('Banquet Hall', Direction::$w, 'Grand Hall')
  ->connectRooms('Grand Hall', Direction::$s, 'Eastern End of Tapestry Room')
  ->connectRooms('Grand Hall', Direction::$n, 'Grand Staircase')
  ->connectRooms('Grand Staircase', Direction::$u, 'Grand Balcony')
  ->connectRooms("Servants' Quarters", Direction::$n, 'Base of Eastern Tower')
  ->connectRooms('Base of Eastern Tower', Direction::$u, 'Top of Eastern Tower')

  // Iteration 2 Second Floor:
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
  
  // Iteration 3 Main Floor:
  ->connectRooms('Back Hallway', Direction::$w, 'Infirmary')
  ->connectRooms('Pantry', Direction::$e, 'Pantry Storage Room')
  ->connectRooms('Library', Direction::$e, 'Cloak Room')
  ->connectRooms('Cloak Room', Direction::$n, 'Western End of Tapestry Room')
  ->connectRooms('Chess Room', Direction::$d, 'Hall of Mirrors')
  
  // Iteration 3 Lower Floor:
  ->connectRooms('Hall of Mirrors', Direction::$w, 'Alcove')
  ->connectRooms('Hall of Mirrors', Direction::$s, 'Rack Room')
  ->connectRooms('Hall of Mirrors', Direction::$e, 'Boiler Room')
  ->connectRooms('Alcove', Direction::$s, 'Treasury Room')
  ->connectRooms('Boiler Room', Direction::$e, 'Wine Cellar')
  ->connectRooms('Boiler Room', Direction::$s, 'Portcullis')
  ->connectRooms('Portcullis', Direction::$s, 'Armory')
  ->connectRooms('Wine Cellar', Direction::$s, 'Cellar Storage Room')
  
  ->setSpawnPoint('Castle Entrance')
;
