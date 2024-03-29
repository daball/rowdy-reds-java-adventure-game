<?php

namespace games;

require_once __DIR__.'/../app/game/GameBuilder.php';
require_once __DIR__.'/../app/game/Direction.php';
require_once __DIR__.'/../app/playable/index.php';
require_once __DIR__.'/../app/components/index.php';
require_once __DIR__.'/../app/java/TabletCompilerService.php';
require_once __DIR__.'/../app/util/Resolver.php';

use \components\Assignable;
use \components\Puzzle;
use \game\GameBuilder;
use \game\GameState;
use \game\Direction;
use \game\Map;
use \game\Room;
use \playable\BasicContainer;
use \playable\Key;
use \playable\Food;
use \playable\GeneralObject;
use \playable\LockedDoor;
use \playable\Dog;
use \util\Resolver;

/* SIMPLE ROOM DEFINITIONS */
$gameName = pathinfo(__FILE__)['filename'];
$forest = array(
  'name'        => "Forest",
  'description' => "You are standing in a forest.  There are trees all around you.  A path leads north.",
  'imageUrl'    => "forest.jpg",
  'items'       => array(
    'note'       => array(
      'type'                    => 'note',
      'name'                    => "sign",
      'description'             => "The sign reads:\n\nI'm a lonely, lonely sign in a lonely, old, creepy forest, and nobody ever visits me. Since you are so very " .
                                   "nice to visit me, I will let you in on a tiny little secret. A lamp can be used for more than just lighting up dark rooms. " .
                                   "Sometimes the extra light can help you see other things hidden in the shadows!  I hope that helps!  Thank you for visiting me!\n\n" .
                                   "Hmm... Seems kind of wordy for an old wooden sign in the creepy old woods, by the craggy cliff, in the creepy country wherever you " .
                                   "are, that nobody goes to, on the a tail on the frog on the bump on the log in the hole in the bottom of the sea.",
    ),
  ),
);
$castleEntrance = array(
  'name'        => "Castle Entrance",
  'description' => "You are at the edge of a forest and are standing in front of a castle.  This castle is a grand residence contain many magnificent rooms to explore. The castle's door lies to the north.",
  'imageUrl'    => "castleEntrance.png",
  'items'       => array(
    'note'       => array(
      'type'                    => 'note',
      'name'                    => "sign",
      'description'             => "The sign reads:\n ->  Warning - Beware of the...\n\nThe rest of the sign appears to be burned away.",
    ),
  ),
);
$foyer = array(
  'name'        => "Foyer",
  'description' => "You are in a foyer. Intricate stonework flooring and the stone walls have been polished until it shines. Decorative lamps line the walls.",
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
      'description'             => "The note reads:\n\nTo whom it may concern,\n\nIt's worthwhile to make sure you don't pick up too many items of limited usefulness. " .
                                   "You can only carry just a couple items at a time, and if you pick up an item in your hand (variable) where you're already carrying " .
                                   "something then the old item is lost forever, possibly leaving you unable to complete your quest.  If that happens, then you can type 'reset' and try your quest again." .
                                   "\n\n You think to yourself that seems kind of limiting..  Sure would be nice if you had a bag or something to carry more stuff.",
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
      'secret'                  => "rusty",
      'description'             => "It's a dingy rusty key.",
      'onAssign.room.imageUrl'  => "library.jpg",
    ),
    'javadocTablet'    => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Tablet\n" .
                                   "public class Tablet\n" .
                                   "extends Object\n\n" .
                                   "This class is empty, but can be used to type in Java methods to be used in the game.\n\n" .
                                   "Method Summary\n==============\n" .
                                   "No Methods Exist Yet"
    ),
  ),
);
$conservatory = array(
  'name'        => "Conservatory",
  'description' => "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.",
  'imageUrl'    => "conservatory.jpg",
  'items'       => array(
    'javadocPlayer'    => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Player\n" .
                                   "public class Player\n" .
                                   "extends Object\n\n" .
                                   "Field Summary\n=============\n" .
                                   "protected Object   leftHand\n" .
                                   "protected Object   rightHand\n\n" .
                                   "Method Summary\n==============\n" .
                                   "void               moveNorth()\n" .
                                   "                   Moves player to a room in the Northern direction, if a room exists in that direction.\n" .
                                   "void               moveEast()\n" .
                                   "                   Moves player to a room in the Eastern direction, if a room exists in that direction.\n" .
                                   "void               moveSouth()\n" .
                                   "                   Moves player to a room in the Southern direction, if a room exists in that direction.\n" .
                                   "void               moveWest()\n" .
                                   "                   Moves player to a room in the Western direction, if a room exists in that direction.\n" .
                                   "void               moveUp()\n" .
                                   "                   Moves player to a room above current position, if a such a passage exists in that direction (i.e. stairs).\n" .
                                   "void               moveDown()\n" .
                                   "                   Moves player to a room below current position, if a such a passage exists in that direction (i.e. stairs).\n" .
                                   "void               equip(Equipment item)\n" .
                                   "                   Allows player to wear a piece of equipment (i.e. wearable items or items with shoulder straps)\n" .
                                   "int                getHealthPoints()\n" .
                                   "                   Returns the number of health points the player has, which can be between 100 and 0.  When player health points reach zero, then the player dies and the game is over.\n"
        ),
    ),
);
$lounge = array(
  'name'        => "Lounge",
  'description' => "You are in a lounge decorated with many paintings, and nice comfortable seating.  There is a door to the east.",
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
  'imageUrl'    => "kitchen_lambchop.jpg",
  'items'       => array(
    'lambChop'    => array(
      'type'        => "food",
      'name'        => "lambChop",
      'description' => "It's a chop of lamb.",
      'onAssign.room.imageUrl'  => "kitchen.jpg",
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
      'secret'                  => "brass",
      'description'             => "It's a nice and shiny brass key.",
      'onAssign.room.imageUrl'  => "pantry.jpg",
    ),
  ),
);
$banquetHall = array(
  'name'        => "Banquet Hall",
  'description' => "You are in the banquet hall. Life of the Medieval castle revolves around the Banquet Hall and its festivities",
  'imageUrl'    => "banquetHall.jpg",
  'items'       => array(
    'lockedDoor'  => array(
      'type'        => "lockedDoor",
      'name'        => "door",
      'direction'   => Direction::$e,
      'secret'  => "NoKeyExists",
    ),
    'doorsPoster1'    => array(
      'type'                    => 'note',
      'name'                    => "poster",
      'description'             => "You examine the poster and are puzzled. It appears to be a poster for the band \"The Doors\" for their album \"Strange Days\"..." .
                                   "\n\nWhat in the world is a poster for The Doors doing in a medieval castle, and why on Earth is it in the banquet hall????",
    ),
  ),
);
$backHallway = array(
  'name'        => "Back Hallway",
  'description' => "You are in a hallway.",
  'imageUrl'    => "hallway1.jpg",
);
$servantsQuarters = array(
  'name'        => "Servants' Quarters",
  'description' => "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.",
  'imageUrl'    => "servantsQuarters.jpg",
  'items'       => array(
    'footLocker'  => array(
      'type'                    => 'lockableContainer',
      'name'                    => "footLocker",
      'secret'                  => $pantry['items']['brassKey']['secret'],
      'description'             => "It's a servant's simple footLocker chest that is sitting on the floor.",
      'onOpen.room.imageUrl'    => "servantsQuarters_lamp.jpg",
      'items'                   => array(
        'lamp'                    => array(
          'type'                    => 'lamp',
          'name'                    => 'lamp',
          'description'             => "You found a lamp.  A lamp can light your way through dark places.",
          'onAssign.room.imageUrl'  => "servantsQuarters.jpg",
        ),
      ),
    ),
  ),
);
$taxidermyRoom = array(
  'name'        => "Taxidermy Room",
  'description' => "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter. " .
                   "One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way " .
                   "of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.",
  'imageUrl'    => "taxidermyRoom_dog.jpg",
  'dog.name'    => "dog",
  'bowl.name'   => "bowl",
  'states'        => array(
    'dogHungry'     => array(
      'state'                   => "hungry",
      'dog.description'         => "It's a sizeable looking dog is sitting by the northern door, watching you alertly.",
      'bowl.description'        => "It's an empty bowl sitting on the floor.",
      'lambChop.onEat.imageUrl' => "taxidermyRoom.jpg",
    ),
    'dogEating'     => array(
      'state'                   => "eating",
      'dog.description'         => "The dog is eating the lambChop.",
      'bowl.description'        => "The dog is licking the bowl clean.",
    ),
    'dogHappy'      => array(
      'state'                   => "happy",
      'dog.description'         => "The dog is now satisfied from eating and smiles at you. You are a new best friend.",
      'bowl.description'        => "Only crumbs remain in the bowl.",
    ),
  ),
);
$chessRoom = array(
  'name'                  => "Chess Room",
  'description'           => "You see a room with a chess board on the floor in front of you.",
  'imageUrl'              => "chessRoom.jpg",
  'dark'                  => true,
  'lamp.wind.imageUrl'    => "",
  'lamp.unwind.imageUrl'  => "darkRoom.jpg",
);
$vestibule = array(
  'name'         => "Vestibule",
  'description'  => "You are in a small vestibule.",
  'imageUrl'     => "vestibule.jpg",
);
$artGallery = array(
  'name'         => "Art Gallery",
  'description'  => "You are in the castle art gallery.",
  'imageUrl'     => "artGallery.jpg",
  'items'       => array(
    'javadocDoor'    => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Door\n" .
                                   "public class Door\n" .
                                   "extends LockableObject\n\n" .
                                   "Method Summary\n==============\n" .
                                   "void               close()\n" .
                                   "                   Closes the door.\n" .
                                   "void               lock(Key key)\n" .
                                   "                   Closes and locks the door with the provided key.\n" .
                                   "void               open()\n" .
                                   "                   Opens the door if it is closed and unlocked.\n" .
                                   "void               unlock(Key key)\n" .
                                   "                   Unlocks door with provided key and opens it.\n"
    ),
  ),
);
$wTowerBase = array(
  'name'         => "Base of Western Tower",
  'description'  => "You are in a circular room with a spiral staircase leading up to the right.",
  'imageUrl'     => "westTower1.jpg",
);
$grandHall = array(
  'name'         => "Grand Hall",
  'description'  => "You are in the Grand Hall. Beautiful high ceiling and a grand statue give this room its name",
  'imageUrl'     => "grandHall.jpg",
);
$grandStaircase = array(
  'name'         => "Grand Staircase",
  'description'  => "You are at a magnificent staircase at the north end of the Grand Hall. The steps are made of decorative marble where king and queens have passed",
  'imageUrl'     => "grandStaircase.jpg",
);
$eTowerBase = array(
  'name'         => "Base of Eastern Tower",
  'description'  => "You are in a circular room with a spiral staircase leading up to the left. The towers are the highest part of the castle were you can see for long distances at the top",
  'imageUrl'     => "eastTower1.jpg",
);
$courtyard = array(
  'name'         => "Courtyard",
  'description'  => "You are in the castle courtyard. This Courtyards provide great comforts which include light, privacy, security, and tranquillity. ",
  'imageUrl'     => "courtyard.jpg",
  'items'       => array(
    'javadocDragon'    => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Dragon\n" .
                                   "public class Dragon\n" .
                                   "extends Enemy\n\n" .
                                   "Method Summary\n==============\n" .
                                   "boolean            isFlying()\n" .
                                   "                   Returns true if the dragon is flying\n" .
                                   "boolean            isInhaling()\n" .
                                   "                   Returns true if the dragon is inhaling so that it may breathe fire.\n" .
                                   "boolean            isAlive()\n" .
                                   "                   Returns true if the dragon is alive."
    ),
    'flower'       => array(
      'type'                    => "generalObject",
      'name'                    => "flower",
      'description'             => "It's a beautiful flower with a pleasant aroma.",
    ),
  ),
);
$stable = array(
  'name'         => "Stable",
  'description'  => "You are in a stable. The horses are all out in the pasture. ",
  'imageUrl'     => "stables.jpg",
  'items'       => array(
    'tablet'       => array(
      'type'                    => "equipment",
      'name'                    => "tablet",
      'description'             => "When equipped, the tablet allows you a place to type full Java methods.",
      'onEquip.description'     => "In order to use the tablet, click on the Tablet Code tab and type Java methods. Once you have your Java methods ready to run, go back to the Command Line tab and enter a method call.",
    ),
    'horseShoe'       => array(
      'type'                    => "generalObject",
      'name'                    => "horseShoe",
      'description'             => "It's a horse shoe.  You wonder if a horse threw it, or if it's just here for luck.",
    ),
    'saddle'       => array(
      'type'                    => "generalObject",
      'name'                    => "saddle",
      'description'             => "It's a well cared for and oiled saddle complete with a saddle blanket.",
    ),
    'whip'       => array(
      'type'                    => "generalObject",
      'name'                    => "whip",
      'description'             => "It's a horse whip.",
    ),
  ),
);
$smithery = array(
  'name'         => "Smithery",
  'description'  => "You are in a smithery. Here the blacksmith would work with a forge making iron utensils, horseshoes, weapons and repairing armour.",
  'imageUrl'     => "smithery.jpg",
  'items'        => array(
    'hammer'       => array(
      'type'                    => "generalObject",
      'name'                    => "hammer",
      'description'             => "It's a good heavy hammer.",
    ),
    'anvil'       => array(
      'type'                    => "generalObject",
      'name'                    => "anvil",
      'description'             => "It's a well used anvil.",
    ),
    'sword'       => array(
      'type'                    => "generalObject",
      'name'                    => "sword",
      'description'             => "It's a basic sword.",
    ),
  ),
);
$balcony = array(
  'name'         => "Grand Balcony",
  'description'  => "You are on a grand balcony that is overlooking the Grand Hall below.",
  'imageUrl'     => "grandBalcony.jpg",
);
$billiards = array(
  'name'         => "Billiards Room",
  'description'  => "You are in a billiards room.",
  'imageUrl'     => "billiardsRoom.jpg",
  'items'       => array(
    'javadocCrank'    => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Crank\n" .
                                   "public class Crank\n" .
                                   "extends Object\n\n" .
                                   "Method Summary\n==============\n" .
                                   "void               setHandle(Handle handle)\n" .
                                   "                   Puts handle in position so the crank can be turned.\n" .
                                   "void               turnCrank()\n" .
                                   "                   Turns the crank one turn.\n" .
                                   "boolean            isOpen()\n" .
                                   "                   Returns true if the crank's portcullis is open and locked into place."

    ),
  ),
);
$mapRoom = array(
  'name'         => "Map Room",
  'description'  => "You are in a strange room were maps once hung on the walls and stories were once told of great travel adventures.",
  'imageUrl'     => "mapRoom.jpg",
);
$drawing = array(
  'name'         => "Drawing Room",
  'description'  => "You are in a Drawing it houses numerous family portraits created by famous painters. Artisans would frequent this room.",
  'imageUrl'     => "drawingRoom.jpg",
  'items'       => array(
    'chessClue'    => array(
      'type'                    => 'note',
      'name'                    => "note",
      'description'             => "The note reads:\n\n" .
                                   "The queen, rooks, and bishops hold considerable power.  They are able to advance across all ranks of the board " .
                                   "in a single move.  Anyone who is able to advance all the ranks on the board in one move is bound to find something special."
    ),
  ),
);
$observatory = array(
  'name'         => "Observatory",
  'description'  => "You are in a sophisticated observatory. A large telescope sits on the floor looking towards the heavens. Books line the walls with information on the constellations.",
  'imageUrl'     => "observatory.jpg",
    'items'       => array(
    'telescope'    => array(
      'type'                    => 'note',
      'name'                    => "telescope",
      'description'             => "You peer into the telescope and expect to see brilliant stars, constellations, and other heavenly bodies, but much " .
                                   "to your surprise, you see that the telescope instead shows you the following:\n\n" .
                                   "Class Key\n" .
                                   "public class Key\n" .
                                   "extends Object\n\n" .
                                   "Key is a class used to unlock any LockableObject.  In order for a LockableObject to be unlocked with a given key " .
                                   "the secretPhrase must match the secretPhrase already within the LockableObject.\n\n" .
                                   "Constructor Summary\n===================\n" .
                                   "Key(String secretPhrase)\n" .
                                   "Constructs a new key with the provided secretPhrase."
    ),
  ),
);
$bedchambers = array(
  'name'         => "Master Bedchambers",
  'description'  => "You are in a lavishly decorated bedroom. A four poster bed covered with crushed velvet blankets and plush pillows sit toward the middle of the room. " .
                    "Everything here is beautiful and luxurious, except for a tacky poster and some crappy old computer with a small note taped to the monitor.",
  'imageUrl'     => "masterBedchambers.jpg",
  'items'       => array(
    'doorsPoster2'    => array(
      'type'                    => 'note',
      'name'                    => "poster",
      'description'             => "A curious poster it is.  It's for the band \"The Doors\" for the album titled \"Other Voices\"",
    ),
    'combinationNote'    => array(
      'type'                    => 'note',
      'name'                    => "note",
      'description'             => "The note reads:\n\n" .
                                "I, the master of the house, have written down this confounded combination so I will not forget it!\n" . 
                                "//  Take c1 and add 143 to it and put that answer into c1\n" .
                                "//  Then, take c1, multiply it by three and subtract seventy-seven and put that answer in c2\n" .
                                "//  Next, multiply c1 and c2 together and put that answer in c3.\n" .
                                "//  Then, divide c2 in half and put that into c2\n" .
                                "//  Next, subtract c1 from c2 and put that answer into c2\n" .
                                "//  Finally, add all three combinations together, and put the answer in c3\n" .
                                "//  Remember to open(c1, c2, c3); on the safe!\n\n" .                                
                                "It is certainly a good thing I wrote this down, because no one will ever figure it out, except for me, since " .
                                "I'm the only one who has this note!  The stupid butler apparently thinks he's the head of security and made me " .
                                "use this idiotic policy all this because I had combination 1 2 3 4 5 on my luggage.  Note to self: fire the butler.\n",
    ),
  ),
);
$bathroom = array(
  'name'         => "Bathroom",
  'description'  => "You are in a bathroom that is off the master bed chambers.",
  'imageUrl'     => "bathroom.jpg",
  'items'       => array(
    'javadocLamp'    => array(
      'type'                    => 'note',
      'name'                    => "bathroomReader",
      'description'             => "You open the Bathroom Reader, expecting to see all kinds of interesting articles of fascination that surely accompanies the " .
                                   "King while he sits on his throne, but instead you're startled to find that book only contains blank pages, except for only one:\n\n" .
                                   "Class Lamp\n" .
                                   "public class Lamp\n" .
                                   "extends Object\n\n" .
                                   "Lamp class is able to bring light to other objects to make things more visible.  This implementation of a lamp is " .
                                   "a magical implementation in that the lamp must be wound in order to produce light.\n\n".
                                   "Method Summary\n==============\n" .
                                   "void               wind()\n" .
                                   "                   Powers the lamp so it will be lit for a short while."
    ),
  ),
);
$corridorN = array(
  'name'         => "North End of Corridor",
  'description'  => "You are in the North End of the corridor.",
  'imageUrl'     => "corridor2fn.jpg",
);
$corridorS = array(
  'name'         => "South End of Corridor",
  'description'  => "You are in the South End of the corridor.",
  'imageUrl'     => "corridor2fs.jpg",
);
$beds = array(
  'name'         => "Odd Bedroom",
  'description'  => "You are in a bedroom with three beds.  A fire crackles in the fireplace, making the room soft, warm, and comfortable.",
  'imageUrl'     => "oddBedroom.jpg",
  'items'       => array(
    'goldilocksKey'    => array(
      'type'                    => 'key',
      'name'                    => "goldilocksKey",
      'secret'                  => "goldilocks",
      'description'             => "It's a gold key.",
    ),
  ),
);
$wTowerTop = array(
  'name'         => "Top of Western Tower",
  'description'  => "You are in the top of a tower.",
  'imageUrl'     => "westTowerTop.jpg",
  'items'       => array(
    'note'       => array(
      'type'                    => 'note',
      'name'                    => "javadoc",
      'description'             => "Class Bed\n" .
                                   "public class Bed\n" .
                                   "extends Object\n\n" .
                                   "Method Summary\n==============\n" .
                                   "boolean            isTooSoft()\n" .
                                   "                   Returns whether or not the bed it too soft.\n" .
                                   "boolean            isTooHard()\n" .
                                   "                   Returns whether or not the bed it too soft.\n"
        ),
    ),
);
$eTowerTop = array(
  'name'         => "Top of Eastern Tower",
  'description'  => "You are in the top of a tower inside a graceful room containing beautiful glass doors.  It's strange, because The Doors look like they " .
                    "would open to nowehere, since you're in the top of a tower!",
  'imageUrl'     => "eastTowerTop.jpg",
  'items'        => array(
    'westDoor'    => array(
      'type'         => "lockedDoor",
      'name'         => "westDoor",
//      'description'  => "It's a beautiful glass door which you clearly see opens to the outside.",
      'direction'   => Direction::$w,
      'secret'  => "Other Voices",
    ),
    'northDoor'    => array(
      'type'         => "lockedDoor",
      'name'         => "northDoor",
//      'description'  => "It's a beautiful glass door which you clearly see opens to the outside.",
      'direction'   => Direction::$n,
      'secret'  => "Strange Days",
    ),
    'eastDoor'    => array(
      'type'         => "lockedDoor",
      'name'         => "eastDoor",
//      'description'  => "It's a beautiful glass door which you clearly see opens to the outside.",
      'direction'   => Direction::$e,
      'secret'  => "Full Circle",
    ),
  ),
  'puzzleReward' => array(
    'type'        => "generalObject",
    'name'        => "magicSword",
    'description' => "It's a magical enchanted sword.  Enscribed on the hilt is the word DRAGONBUSTER.",
  ),
);
$infirmary = array(
  'name'         => "Infirmary",
  'description'  => "You are in an infirmary. After large epic battles this room would be care for the injured and dying.",
  'imageUrl'     => "infirmary.jpg",
  'items'       => array(
     'healingSalve'      => array(
      'type'                    => "generalObject",
      'name'                    => "healingSalve",
      'description'             => "It is a jar of Wierdy Beardy's Magical Convoluted Enchanted Healing Salve. " .
                                   "It says, \"Guaranteed to work in a pinch! At least until it's all gone...\"",
    ),                               
  ),                                   
);
$pantryStorage = array(
  'name'         => "Pantry Storage Room",
  'description'  => "You are in what appears to be a storage room.",
  'imageUrl'     => "pantryStorage_handle.jpg",
    'items'       => array(
     'handle'      => array(
      'type'                    => "generalObject",
      'name'                    => "handle",
      'description'             => "It is a handle of some sort.  I wonder what it goes to?",
      'onAssign.room.imageUrl'  => "pantryStorage.jpg",
    ),
  ),
);
$cloakRoom = array(
  'name'         => "Cloak Room",
  'description'  => "You are in a small alcove for storing cloaks, gloves, and the like.",
  'imageUrl'     => "cloakRoom.jpg",
  'items'       => array(
    'clothCloak'       => array(
      'type'                    => "equipment",
      'name'                    => "clothCloak",
      'description'             => "It's an old cloth cloak.",
      'onEquip.description'     => "You put on the old cloth cloak.  It makes you feel warm.",
    ),

    'crystalCloak'     => array(
      'type'                    => "equipment",
      'name'                    => "crystalCloak",
      'description'             => "It's a magically enchanted cloak.  It almost looks transparent and the cloth feels light and airy.  There's not really " .
                                   "any other way to describe it other than calling it a crystal cloak.  The very curious thing is that when you run your hand " .
                                   "behind the cloak, it looks like your hand disappears!  Very curious!",
      'onEquip.description'     => "You put on the crystal cloak.  Somehow you feel lighter and more nimble.",
    ),
  ),
);
$hallMirrors = array(
  'name'         => "Hall of Mirrors",
  'description'  => "You are in a hall of mirrors. If you look too closely your eyes will deceive you making it difficult to figure out what the true dimensions of this room really are.",
  'imageUrl'     => "hallofMirrors.jpg",
  'items'       => array(
    'backpack'       => array(
      'type'                    => 'backpack',
      'name'                    => "backpack",
      'description'             => "It is a backpack.  You can use it like a Java array when equipped.",
      'onEquip.description'     => "You have equipped the backpack. You can use it like a Java array.",
    ),
  ),
);
$alcove = array(
  'name'         => "Alcove",
  'description'  => "You are in a small alcove.  There is a large door to the south.",
  'imageUrl'     => "alcove.jpg",
  'items'       => array(
    'lockedDoor'  => array(
      'type'        => "lockedDoor",
      'name'        => "door",
      'direction'   => Direction::$s,
      'secret'  => "goldilocks",
    ),
  ),
);
$treasury = array(
  'name'         => "Treasury Room",
  'description'  => "You are in a massive treasury room, and in front of you, guarding the treasure hoard, is a huge red dragon!",
  'imageUrl'     => "treasury.jpg",
  'items'       => array(
    'dragon'    => array(
      'type'                    => 'dragon',
      'name'                    => "dragon",
      'description'             => "It's a massive red dragon!",
    ),
  ),
);
$rackRoom = array(
  'name'         => "Rack Room",
  'description'  => "You are in a rack room.  There are various devices of torture all around.  You really don't like being here.",
  'imageUrl'     => "rackRoom.jpg",
);
$boiler = array(
  'name'         => "Boiler Room",
  'description'  => "You are in a boiler boom. Strange noises come from all the weird machinery. ",
  'imageUrl'     => "boilerRoom.jpg",
  'items'       => array(
    'doorsPoster3'    => array(
      'type'                    => 'note',
      'name'                    => "poster",
      'description'             => "A boiler room is such an odd place for a poster!  It's for the band \"The Doors\" for the album titled \"Full Circle\"",
    ),
  ),
);
$portcullis = array(
  'name'         => "Portcullis",
  'description'  => "You are in a room with a portcullis (large gate) on the southern side.  You also see a winch on the wall.",
  'imageUrl'     => "portcullis.jpg",
  'items'       => array(
    'portcullis'  => array(
      'type'        => "lockedDoor",
      'name'        => "portcullis",
      'direction'   => Direction::$s,
      'secret'  => "arbitrary password",
    ),
    'crank'  => array(
      'type'        => "generalObject",
      'name'        => "crank",
    ),
  ),
);
$armory = array(
  'name'         => "Armory",
  'description'  => "You are in an armory. Knights used the the equipment in epic battles. ",
  'imageUrl'     => "armory_shield.jpg",
  'items'       => array(
    'shield'    => array(
      'type'                    => 'generalObject',
      'name'                    => "shield",
      'description'             => "It's a beautiful black shield with a red incription of a dragon's head.",
      'onAssign.room.imageUrl'  => "armory.jpg",
    ),
  ),
);
$wineCellar  = array(
  'name'         => "Wine Cellar",
  'description'  => "You are in a wine cellar.",
  'imageUrl'     => "wineCellar.jpg",
);
$cellarStorage = array(
  'name'         => "Cellar Storage Room",
  'description'  => "You are in a cellar storage room of sorts.",
  'imageUrl'     => "cellarStorage.jpg",
  'items'       => array(
    'safe'  => array(
      'type'                    => 'lockableContainer',
      'name'                    => "safe",
      'secret'                  => "o[sidfgj120454121hosidfjh]osidf&^%*&%*jiosdfjibosdfjoisdj",
      'description'             => "It's a decent sized heavy safe.  There's three combination digits on there, but you can't see the numbers for some reason. " .
                                   "You can only see ??? where the numbers are supposed to be. " .
                                   "They are inscribed as:\n\nint c1 = ??? , c2 = ???, c3 = ???;  // combination digits",
//      'onOpen.room.imageUrl'    => "cellarStorage_crossbow.jpg",
      'items'                   => array(
        'lamp'                    => array(
          'type'                    => 'generalObject',
          'name'                    => 'crossbow',
          'description'             => "It's an awesome looking magical crossbow!  There's no bolts needed with this crossbow, as it fires magical bolts.",
          'onAssign.room.imageUrl'  => "cellarStorage.jpg",
        ),
      ),
    ),
  ),
);
$limbo = array(
  'name'         => "Limbo",
  'description'  => "You are floating in limbo.  The feeling of death is all around you, but, you feel that you may still have a way.  Perhaps you have another chance?",
  'imageUrl'     => "limbo.jpg",
);
$gameOver = array(
  'name'         => "Certificate of Completion",
  'description'  => "Congratulations on your defeat of the dragon.  You have been awarded the Certificate of Excellence for defeating the dragon.\nGame credits: David A. Ball, Richard Hay, Brittany Lester, Sean Rider, Steven Tanner, Brandon Wilcox.  You may exit or restart the game.",
  'imageUrl'     => "Certificate.png",
);

GameBuilder::newGame($gameName)
  ->insertRoom($forest)
  ->insertRoomAt($forest,           Direction::$n,    $castleEntrance)
  ->insertRoomAt($castleEntrance,   Direction::$n,    $foyer)
  ->insertRoomAt($foyer,            Direction::$n,    $tapestryE)
  ->insertRoomAt($tapestryE,        Direction::$w,    $tapestryW)
  ->insertRoomAt($tapestryW,        Direction::$w,    $study)
  ->insertRoomAt($study,            Direction::$s,    $library)
  ->insertRoomAt($foyer,            Direction::$e,    $conservatory)
  ->insertRoomAt($conservatory,     Direction::$e,    $lounge)
  ->insertRoomAt($lounge,           Direction::$e,    $butlersQuarters)
  ->insertRoomAt($butlersQuarters,  Direction::$n,    $kitchen)
  ->insertRoomAt($kitchen,          Direction::$n,    $pantry)
  ->insertRoomAt($kitchen,          Direction::$w,    $banquetHall)
  ->connectRooms($banquetHall,      Direction::$s,    $conservatory) // connected already existing rooms
  ->insertRoomAt($banquetHall,      Direction::$n,    $backHallway)
  ->insertRoomAt($backHallway,      Direction::$e,    $servantsQuarters)

  ->insertRoom(\game\assembleRoom($taxidermyRoom)->define(function ($room) use ($taxidermyRoom, $gameName) {
    $roomName = $room->getName();
    $dogUrl = "game://$gameName/$roomName/StateMachine:Dog+Bowl";
    $room->publish($dogUrl, $taxidermyRoom['states']['dogHungry']['state']);
    $room->getComponent('Container')->insertItem((new Dog($taxidermyRoom['dog.name'], Direction::$n))->define(function ($dog) use ($dogUrl, $room, $taxidermyRoom) {
      $foodConsumer = $dog->getComponent('FoodConsumer');
      $foodConsumer->onEat(function ($foodConsumer, $food) use ($taxidermyRoom) {
        $dog = $foodConsumer->getParent();
        $room = $dog->getContainer();
        $room->setImageUrl($taxidermyRoom['states']['dogHungry']['lambChop.onEat.imageUrl']);
      });
      $dog->subscribe($dogUrl, function ($sender, $queue, $stateOfDog) use ($dogUrl, $room, $dog, $taxidermyRoom) {
        $lambChop = $room->getComponent('Container')->findNestedItemByName('lambChop');
        $foodConsumer = $dog->getComponent('FoodConsumer');
        $inspector = $dog->getComponent('Inspector');
        $dogMessage = "";
        switch ($stateOfDog) {
          case $taxidermyRoom['states']['dogHungry']['state']:
            $dogMessage = $taxidermyRoom['states']['dogHungry']['dog.description'];
            break;
          case $taxidermyRoom['states']['dogEating']['state']:
            $dogMessage = $taxidermyRoom['states']['dogEating']['dog.description'];
            $foodConsumer->eat($lambChop);
            break;
          case $taxidermyRoom['states']['dogHappy']['state']:
            $dogMessage = $taxidermyRoom['states']['dogHappy']['dog.description'];
            break;
        }
        if ($dogMessage) {
          $inspector->popEventHandler('inspect');
          $inspector->onInspect(function ($inspector) use ($dogUrl, $dogMessage, $taxidermyRoom) {
            if ($dogMessage == $taxidermyRoom['states']['dogEating']['dog.description'])
              $inspector->publish($dogUrl, $taxidermyRoom['states']['dogHappy']['state']);
            return $dogMessage;
          });
        }
      });
    }));
    $room->getComponent('Container')->insertItem((new BasicContainer($taxidermyRoom['bowl.name']))->define(function ($bowl) use ($dogUrl, $taxidermyRoom) {
      $bowl->subscribe($dogUrl, function ($sender, $queue, $message) use ($dogUrl, $bowl, $taxidermyRoom) {
        $bowlMessage = "";
        switch ($message) {
          case $taxidermyRoom['states']['dogHungry']['state']:
            $bowlMessage = $taxidermyRoom['states']['dogHungry']['bowl.description'];
            break;
          case $taxidermyRoom['states']['dogEating']['state']:
            $bowlMessage = $taxidermyRoom['states']['dogEating']['bowl.description'];
            break;
          case $taxidermyRoom['states']['dogHappy']['state']:
            $bowlMessage = $taxidermyRoom['states']['dogHappy']['bowl.description'];
            break;
        }
        if ($bowlMessage) {
          $inspector = $bowl->getComponent('Inspector');
          $inspector->popEventHandler('inspect');
          $inspector->onInspect(function ($inspector) use ($dogUrl, $bowlMessage, $taxidermyRoom) {
            if ($bowlMessage == $taxidermyRoom['states']['dogEating']['bowl.description'])
              $inspector->publish($dogUrl, $taxidermyRoom['states']['dogHappy']['state']);
            return $bowlMessage;
          });
        }
      });
      $bowl->getComponent('Container')->setValidItemTypes(array('\playable\Food'));
      $bowl->getComponent('Container')->onSet(function ($container, $index, $item) use ($dogUrl, $taxidermyRoom) {
        $bowl = $container->getParent();
        $bowl->publish($dogUrl, $taxidermyRoom['states']['dogEating']['state']);
        $consumer = $taxidermyRoom['dog.name'];
        $bowl = $bowl->getName();
        $item = $item->getName();
        return "The $consumer runs over and starts eating $item from the $bowl.";
      });
    }));
  }))
  ->connectRooms($tapestryW,        Direction::$n,    $taxidermyRoom)
  ->insertRoom(\game\assembleRoom($chessRoom)->define(function ($room) use ($chessRoom, $gameName, $hallMirrors) {
    $room->addComponent((new Puzzle())->define(function ($puzzle) use ($chessRoom, $hallMirrors) {
      $puzzle->popEventHandler('headerCode');
      $puzzle->setHeaderCode(function ($puzzle) {
        return "chessBoard = new ChessBoard();\n";
      });
      $puzzle->popEventHandler('beforeSolve');
      $puzzle->onBeforeSolve(function ($puzzle, $javaTabletInstance) {
        return java_values($javaTabletInstance->chessBoard->isSolved());
      });
      $initialOnSolve = $puzzle->popEventHandler('solve');
      $puzzle->onSolve(function ($puzzle, $javaTabletInstance) use ($chessRoom, $initialOnSolve, $hallMirrors) {
        //setup the room connection here
        $puzzle->getParent()->connectToRoom("down", $hallMirrors['name']);
        $puzzle->getParent()->setImageUrl('chessRoom_stairs.jpg');
        return $initialOnSolve($puzzle, $javaTabletInstance);
      });
    }));
  }))
  ->connectRooms($taxidermyRoom,    Direction::$n,    $chessRoom)

  ->insertRoom($limbo)
  ->oneWayConnectRoom($limbo,       Direction::$n,    $forest)
  ->oneWayConnectRoom($limbo,       Direction::$e,    $forest)
  ->oneWayConnectRoom($limbo,       Direction::$s,    $forest)
  ->oneWayConnectRoom($limbo,       Direction::$w,    $forest)
  ->oneWayConnectRoom($limbo,       Direction::$u,    $forest)
  ->oneWayConnectRoom($limbo,       Direction::$d,    $forest)
    // Iteration 2 Room Connections
  ->insertRoomAt($foyer,            Direction::$w,    $vestibule)
  ->insertRoomAt($vestibule,        Direction::$w,    $wTowerBase)
  ->insertRoomAt($wTowerBase,       Direction::$u,    $wTowerTop)
  ->insertRoomAt($study,            Direction::$n,    $artGallery)
  ->insertRoomAt($kitchen,          Direction::$e,    $courtyard)
  ->insertRoomAt($courtyard,        Direction::$e,    $stable)
  ->insertRoomAt($stable,           Direction::$s,    $smithery)
  ->insertRoomAt($banquetHall,      Direction::$w,    $grandHall)
  ->connectRooms($grandHall,        Direction::$s,    $tapestryE) // connected already existing rooms
  ->insertRoomAt($grandHall,        Direction::$n,    $grandStaircase)
  ->insertRoomAt($grandStaircase,   Direction::$u,    $balcony)
  ->insertRoomAt($servantsQuarters, Direction::$n,    $eTowerBase)
  ->insertRoom(\game\assembleRoom($eTowerTop)->define(function ($room) use ($eTowerTop, $gameName) {
    $container = $room->getComponent("Container");
    $westDoor = $container->findItemByName("westDoor");
    $westDoorInitialOnUnlock = $westDoor->getComponent("Lockable")->popEventHandler('unlock');
    $westDoor->getComponent("Lockable")->onUnlock(function ($lockable, $keyProvided) use ($westDoorInitialOnUnlock) {
      $lockable->publish("TheDoorsPuzzle", $lockable->getParent()->getName());
      return $westDoorInitialOnUnlock($lockable, $keyProvided);
    });
    $eastDoor = $container->findItemByName("eastDoor");
    $eastDoorInitialOnUnlock = $eastDoor->getComponent("Lockable")->popEventHandler('unlock');
    $eastDoor->getComponent("Lockable")->onUnlock(function ($lockable, $keyProvided) use ($eastDoorInitialOnUnlock) {
      $lockable->publish("TheDoorsPuzzle", $lockable->getParent()->getName());
      return $eastDoorInitialOnUnlock($lockable, $keyProvided);
    });
    $northDoor = $container->findItemByName("northDoor");
    $northDoorInitialOnUnlock = $northDoor->getComponent("Lockable")->popEventHandler('unlock');
    $northDoor->getComponent("Lockable")->onUnlock(function ($lockable, $keyProvided) use ($northDoorInitialOnUnlock) {
      $lockable->publish("TheDoorsPuzzle", $lockable->getParent()->getName());
      return $northDoorInitialOnUnlock($lockable, $keyProvided);
    });
    $doorsOpened = array();
    $magicalSword = \game\assembleGeneralObject($eTowerTop, $room, $eTowerTop['puzzleReward']);
    $room->subscribe("TheDoorsPuzzle", function ($sender, $queue, $message) use ($room, $eTowerTop, $magicalSword, &$doorsOpened) {
      $doorsOpened[$message] = true;
      $doorsToGo = 3 - count($doorsOpened);
      $lockable = $sender;
      $door = $lockable->getParent();
      $room = $door->getContainer();
      $container = $room->getComponent("Container");
      if ($doorsToGo > 0) {
        $room->publish("System.out", "$doorsToGo doors to go.");
      }
      else if ($doorsToGo == 0) {
        $container->insertItem($magicalSword);
        $room->publish("System.out", "You have solved the puzzle.  What's that you see over there?");
      }
      $doorsToGo--;
    });
  }))
  ->connectRooms($eTowerBase,       Direction::$u,    $eTowerTop)

  // Second Floor:
  ->insertRoomAt($balcony,          Direction::$e,    $drawing)
  ->insertRoomAt($balcony,          Direction::$w,    $observatory)
  ->insertRoomAt($observatory,      Direction::$s,    $mapRoom)
  ->insertRoomAt($balcony,          Direction::$s,    $corridorN)
  ->insertRoomAt($corridorN,        Direction::$s,    $corridorS)
  ->connectRooms($corridorN,        Direction::$w,    $mapRoom) // connected already existing rooms
  ->insertRoomAt($corridorN,        Direction::$e,    $billiards)
  ->insertRoomAt($corridorS,        Direction::$w,    $beds)
  ->insertRoomAt($corridorS,        Direction::$e,    $bedchambers)
  ->insertRoomAt($bedchambers,      Direction::$n,    $bathroom)

  // Iteration 3 Main Floor:
  ->insertRoomAt($backHallway,      Direction::$w,    $infirmary)
  ->insertRoomAt($pantry,           Direction::$w,    $pantryStorage)
  ->insertRoomAt($library,          Direction::$e,    $cloakRoom)
  ->connectRooms($cloakRoom,        Direction::$n,    $tapestryW)
//  ->insertRoomAt($chessRoom,        Direction::$d,    $hallMirrors)
  ->insertRoom($hallMirrors)

  // Iteration 3 Lower Floor:
  ->insertRoomAt($hallMirrors,      Direction::$w,    $alcove)
  ->insertRoomAt($hallMirrors,      Direction::$s,    $rackRoom)
  ->insertRoomAt($hallMirrors,      Direction::$e,    $boiler)
  ->insertRoom(\game\assembleRoom($gameOver))
  ->insertRoom(\game\assembleRoom($treasury)->define(function ($room) use ($treasury, $limbo, $gameOver) {
    $room->addComponent((new Puzzle())->define(function ($puzzle) use ($treasury, $limbo, $gameOver) {
      $puzzle->popEventHandler('headerCode');
      $puzzle->setHeaderCode(function ($puzzle) use ($limbo) {
        $swordResolver = $puzzle->resolve("sword");
        $magicSwordResolver = $puzzle->resolve("magicSword");
        $shieldResolver = $puzzle->resolve("shield");
        $crossbowResolver = $puzzle->resolve("crossbow");

        $initCode = 'dragon = new Dragon("Dragon", 1000);'
                  . 'me = new Player("Rowdy Red", 100);'
                  . 'healingSalve = new Salve(7, 50);'
                  ;
        //init sword
        if ($swordResolver->result()
          // && ($swordResolver->resolve()->getContainer()->getName() == 'leftHand'
          //   || $swordResolver->resolve()->getContainer()->getName() == 'rightHand')
            )
            //init standard issue sword
            $initCode .= 'sword = new Weapon("sword", 45, 0, me); magicSword=sword;';
        //init magic sword
        else if ($magicSwordResolver->result()
          // && ($magicSwordResolver->resolve()->getContainer()->getName() == 'leftHand'
          //   || $magicSwordResolver->resolve()->getContainer()->getName() == 'rightHand')
          )
            //init ass kicking sword
            $initCode .= 'sword = new Weapon("sword", 75, 0, me); magicSword=sword;';
        //init no sword
        else
            //init no sword, just an empty fist
            $initCode .= 'sword = new Weapon("sword", 1, 0, me); magicSword=sword;';
        //init shield
        if ($shieldResolver->result()
          // && ($shieldResolver->resolve()->getContainer()->getName() == 'leftHand'
          //   || $shieldResolver->resolve()->getContainer()->getName() == 'rightHand')
            )
            //init shield
            $initCode .= 'shield = new Shield(0.91, 0.80);';
        //init no shield, only block, not effective
        else
            $initCode .= 'shield = new Shield(0.02, 0.01);';
        //init crossbow
        if ($crossbowResolver->result()
          // && ($crossbowResolver->resolve()->getContainer()->getName() == 'leftHand'
          //   || $crossbowResolver->resolve()->getContainer()->getName() == 'rightHand')
            )
            //init crossbow
            $initCode .= 'crossbow = new Weapon("crossbow", 40, 35, me);';
        //init no crossbow, only throw rocks
        else
            $initCode .= 'crossbow = new Weapon("crossbow", 8, 5, me);';
        return $initCode;
      });
      $puzzle->popEventHandler('beforeSolve');
      $puzzle->onBeforeSolve(function ($puzzle, $javaTabletInstance) {
        //$resolver = $puzzle->resolve("handle");
        return (/*$resolver->result() &&*/ !java_values($javaTabletInstance->dragon->isAlive()));
      });
      $initialOnSolve = $puzzle->popEventHandler('solve');
      $puzzle->onSolve(function ($puzzle, $javaTabletInstance) use ($initialOnSolve, $gameOver) {
        $room = $puzzle->getParent();
        $puzzle->publish("Player", array(
          'action' => 'setLocation',
          'oldLocation' => $room->getName(),
          'newLocation' => $gameOver['name'],
        ));
        return "You defeated the dragon!";
      });
      $puzzle->popEventHandler('refuseSolve');
      $puzzle->onRefuseSolve(function ($puzzle, $javaTabletInstance) use ($initialOnSolve, $limbo) {
        $room = $puzzle->getParent();
        $puzzle->publish("Player", array(
          'action' => 'setLocation',
          'oldLocation' => $room->getName(),
          'newLocation' => $limbo['name'],
        ));
        return "\nYou died!\n" ; //. GameState::getInstance()->inspectRoom();
      });
    }));
  }))
  ->connectRooms($alcove,           Direction::$s,    $treasury['name'])
  ->insertRoomAt($boiler,           Direction::$e,    $wineCellar)
  ->insertRoom(\game\assembleRoom($portcullis)->define(function ($room) use ($portcullis, $gameName) {
    $door = $room->getComponent("Container")->findItemByName($portcullis['items']['portcullis']['name']);
    $lockable = $door->getComponent("Lockable");
    $lockable->popEventHandler('beforeUnlock');
    $lockable->popEventHandler('beforeLock');
    $lockable->onLock(function ($lockable, $key) { return true; });
    $lockable->onUnlock(function ($lockable, $key) { return false; });
    $room->addComponent((new Puzzle())->define(function ($puzzle) use ($portcullis) {
      $puzzle->popEventHandler('headerCode');
      $puzzle->setHeaderCode(function ($puzzle) {
        return "portcullis = new Portcullis(key);" .
               "crank = new Crank(portcullis, key);" .
               "handle = new Handle();";
      });
      $puzzle->popEventHandler('beforeSolve');
      $puzzle->onBeforeSolve(function ($puzzle, $javaTabletInstance) {
        $resolver = $puzzle->resolve("handle");
        return ($resolver->result() && java_values($javaTabletInstance->portcullis->isRaised()));
      });
      $initialOnSolve = $puzzle->popEventHandler('solve');
      $puzzle->onSolve(function ($puzzle, $javaTabletInstance) use ($portcullis, $initialOnSolve) {
        $room = $puzzle->getParent();
        $container = $room->getComponent("Container");
        $door = $container->findItemByName("portcullis");
        $lockable = $door->getComponent("Lockable");
        $openable = $door->getComponent("Openable");
        $collider = $door->getComponent("Collider");
        $lockable->setUnlocked();
        $openable->setOpened();
        $collider->disableCollisions();
        $room->setImageUrl('portcullis_open.jpg');
        return $initialOnSolve($puzzle, $javaTabletInstance) . "  The door is now open.";
      });
      $initialOnRefuseSolve = $puzzle->popEventHandler('refuseSolve');
      $puzzle->onRefuseSolve(function ($puzzle, $javaTabletInstance) use ($portcullis, $initialOnRefuseSolve) {
        $resolver = $puzzle->resolve("handle");
        if (!$resolver->result())
          return "It looks like there is a place for a handle.  You can't possibly turn the crank without a handle.";
        return $initialOnRefuseSolve($puzzle, $javaTabletInstance);
      });
    }));
  }))
  ->connectRooms($taxidermyRoom,    Direction::$n,    $chessRoom)
  ->connectRooms($boiler,           Direction::$s,    $portcullis)
  ->insertRoomAt($portcullis,       Direction::$s,    $armory)
  ->insertRoom(\game\assembleRoom($cellarStorage)->define(function ($room) use ($cellarStorage, $gameName) {
    $room->addComponent((new Puzzle())->define(function ($puzzle) use ($cellarStorage) {
      $puzzle->popEventHandler('headerCode');
      $puzzle->setHeaderCode(function ($puzzle) {
        return "safe = new Combination();\n" .
               "c1 = safe.getStart();\n" .
               "c2 = 0;\n" .
               "c3 = 0;\n";
      });
      $puzzle->popEventHandler('beforeSolve');
      $puzzle->onBeforeSolve(function ($puzzle, $javaTabletInstance) {
        return java_values($javaTabletInstance->safe->isOpen());
      });
      $initialOnSolve = $puzzle->popEventHandler('solve');
      $puzzle->onSolve(function ($puzzle, $javaTabletInstance) use ($cellarStorage, $initialOnSolve) {
        $room = $puzzle->getParent();
        $container = $room->getComponent("Container");
        $safe = $container->findItemByName("safe");
        $lockable = $safe->getComponent("Lockable");
        $openable = $safe->getComponent("Openable");
        $lockable->setUnlocked();
        $openable->setOpened();
        //$room->setImageUrl('cellarStorage.jpg');
        return $initialOnSolve($puzzle, $javaTabletInstance) . "  You have cracked the safe.  Take a look inside.";
      });
    }));
  }))
  ->connectRooms($wineCellar,       Direction::$s,    $cellarStorage)

  ->setSpawnPoint('Castle Entrance');
