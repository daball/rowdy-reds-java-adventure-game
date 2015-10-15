<?php
	// Room Descriptions Array
	$roomDescriptions = array(
        
        // Iteration 1 Rooms:
        "forest"            => "You are standing in a forest.  There are trees all around you.  A path leads north.",
        "castleEntrance"    => "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.",
        "foyer"             => "You are in the castle foyer.",
        "tapestryE"         => "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.",
        "tapestryW"         => "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.",
        "study"             => "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.",
        "library"           => "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.",
        "conservatory"      => "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.",
        "lounge"            => "You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.",
        "butlersQuarters"   => "You are in the butler's quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!",
        "kitchen"           => "You are in the kitchen.  The smell of freshly cooked meat still lingers heavily in the air.",
        "pantry"            => "You descend down some stairs into in the kitchen pantry.  The pantry is stocked with many dry goods.",
        "banquetHall"       => "You are in the banquet hall.",
        "hallway1"          => "You are in a hallway.",
        "servantsQuarters"  => "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.",
        "taxidermyRoom"     => "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.",
        "chessRoom"         => "This room is pitch black.  You can't see anything.",
        
        //Iteration 2 Rooms:
        // main floor:
        "vestibule"         => "You are in a small vestibule.",
        "artGallery"        => "You are in the castle art gallery.",
        "westTower1"        => "You are in a circular room with a spiral staircase leading up to the right.",
        "grandHall"         => "You are in the Grand Hall.",
        "grandStaircase"    => "You are at a magnificant staircase at the north end of the Grand Hall.",
        "eastTower1"        => "You are in a circular room with a spiral staircase leading up to the left.",
        "courtyard"         => "You are in the castle courtyard.",
        "stables"           => "You are in the stables.",
        "smithery"          => "You are in a smithery.",
        // upper floor:
        "grandBalcony"      => "You are on a grand balcony that is overlooking the Grand Hall below.",
        "billiardsRoom"     => "You are in a billiards room.",
        "mapRoom"           => "You are in a strange room with several globes.  The walls are all covered with maps.",
        "drawingRoom"       => "You are in a room with several musical instruments, an easel, some jars of paint, a tilted table, and various drawing utensils.",
        "observatory"       => "You are in a run down obervatory.  The walls are peeling, and old drapes cover tall floor to ceiling windows.  An old telescope sits on the floor.",
        "masterBedchambers"     => "You are in a lavishly decorated bedroom.  A four poster bed covered with crushed velvet blankets and plush pillows sit toward the middle of the room.",
        "masterWashroom"    => "You are in some sort of bathroom that is off the master suite.",
        "bedroom1"          => "You are in a bedroom with three beds.  A fire crackles in the fireplace, making the room soft, warm, and comfortable.  You see a rocking chair, and a vanity with a mirror.",
        // tower tops:
        "westTowerTop"      => "You are in the top of a tower.",
        "eastTowerTop"      => "You are in the top of a tower.",
        
        //Iteration 3 Rooms:
    
	);
	
	// Room Image Array
	if(!isset($_SESSION['roomImage']))
	{
		$_SESSION['roomImage'] = array(
			// Iteration 1 Room Images:
			"forest" => "forest.jpg",
			"castleEntrance" => "castleEntrance.png",
			"foyer" => "foyer.jpg",
			"tapestryE" => "tapestryE.jpg",
			"tapestryW" => "tapestryW.jpg",
			"study" => "study.jpg",
			"library" => "library_key.jpg",
			"conservatory" => "conservatory.jpg",
			"lounge" => "lounge.jpg",
			"butlersQuarters" => "butlersQuarters.jpg",
			"kitchen" => "kitchen.jpg",
			"pantry" => "pantry_key.jpg",
			"banquetHall" => "banquetHall.jpg",
			"hallway1" => "hallway1.jpg",
			"servantsQuarters" => "servantsQuarters.jpg",
			"taxidermyRoom" => "taxidermyRoom_dog.jpg",
			"chessRoom" => "darkRoom.jpg",
			
			// Iteration 2 Rooms:
			// Main Floor:
			"vestibule"         => "vestibule.jpg",
			"artGallery"        => "artGallery.jpg",
			"westTower1"        => "westTower1.jpg",
			"grandHall"         => "grandHall.jpg",
			"grandStaircase"    => "grandStaircase.jpg",
			"eastTower1"        => "eastTower1.jpg",
			"courtyard"         => "courtyard.jpg",
			"stables"           => "stables.jpg",
			"smithery"          => "smithery.jpg",
			// upper floor:
			"grandBalcony"      => "grandBalcony.jpg",
			"billiardsRoom"     => "billiardsRoom.jpg",
			"mapRoom"           => "mapRoom.jpg",
			"drawingRoom"       => "drawingRoom.jpg",
			"observatory"       => "observatory.jpg",
			"masterBedchambers" => "masterBedchambers.jpg",
			"gerderobe"         => "gerderobe.jpg",
			"bedroom1"          => "bedroom1.jpg",
			"corridor2fn"       => "corridor2fn",
			"corridor2fs"       => "corridor2fs",
			"goldilocksRoom"    => "goldilocksRoom",
			
			// tower tops:
			"westTowerTop"      => "westTowerTop.jpg",
			"eastTowerTop"      => "eastTowerTop.jpg",
			
		);
	}
	
	// Directions Array
	$roomConnections = array(
		
        // Iteration 1 Room Directions
        "forestNorth"           => "castleEntrance",
	    
    	"castleEntranceNorth"   => "foyer",
		"castleEntranceSouth"   => "forest",
		
		"foyerSouth"            => "castleEntrance",
		"foyerNorth"            => "tapestryE",
		"foyerEast"             => "conservatory",
        "foyerWest"             => "vestibule",            // connection to i2 room
        	
		"tapestryESouth"        => "foyer",
        "tapestryEWest"         => "tapestryW",
        "tapestryENorth"        => "grandHall",            // connection to i2 room
        
        "tapestryWEast"         => "tapestryE",
        "tapestryWWest"         => "study",
        "tapestryWNorth"        => "taxidermyRoom",
        
        "taxidermyRoomSouth"    => "tapestryW",
        "taxidermyRoomNorth"    => "chessRoom",
        
        "chessRoomSouth"        => "taxidermyRoom",

        "studyEast"             => "tapestryW",
        "studySouth"            => "library",
        "studyNorth"            => "artGallery",           // connection to i2 room
        
        "libraryNorth"          => "study",

        "conservatoryWest"      => "foyer",       
        "conservatoryEast"      => "lounge",
        "conservatoryNorth"     => "banquetHall",
        
        "loungeEast"            => "butlersQuarters",
        "loungeWest"            => "conservatory",
        
        "butlersQuartersWest"   => "lounge",
        "butlersQuartersNorth"  => "kitchen",
        
        "kitchenSouth"          => "butlersQuarters",
        "kitchenWest"           => "banquetHall",
        "kitchenNorth"          => "pantry",
        "kitchenEast"           => "courtyard",            // connection to i2 room
        
        "pantrySouth"           => "kitchen",

        "banquetHallSouth"      => "conservatory",
        "banquetHallNorth"      => "hallway1",
        "banquetHallEast"       => "kitchen",
        "banquetHallWest"       => "grandHall",            // connection to i2 room

        "hallway1South"         => "banquetHall",

        "hallway1East"          => "servantsQuarters",

        "servantsQuartersWest"  => "hallway1",
        "servantsQuartersNorth" => "eastTower1",           // connection to i2 room

        
        // Iteration 2 Room Directions
        // main floor
        "vestibuleEast"         => "foyer",
        "vestibuleWest"         => "westTower1",
        
        "westTower1East"        => "vestibule",
        "westTower1Up"          => "westTowerTop",         // connection to tower top
        
        "westTowerTopDown"      => "westTower1",
        
        "artGallerySouth"       => "study",
        
        "courtyardWest"         => "kitchen",
        "courtyardEast"         => "stables",

        "stablesWest"           => "courtyard",
        "stablesSouth"          => "smithery",

        "smitheryNorth"         => "stables",
        
        "grandHallSouth"        => "tapestryE",
        "grandHallEast"         => "banquetHall",
        "grandHallNorth"        => "grandStaircase",
        
        "grandStaircaseSouth"   => "grandHall",
        "grandStaircaseUp"      => "grandBalcony",         // connection to upper floor i2 room
        
        "eastTower1South"       => "servantsQuarters",
        "eastTower1Up"          => "eastTowerTop",         // connection to tower top
        
        "eastTowerTopDown"      => "eastTower1",

        // upper floor
        "grandBalconyDown"      => "grandStaircase",
		"drawingRoomEast"       => "grandBalcony",
		"grandBalconyWest"      => "drawingRoom",
		"grandBalconyEast"      => "observatory",
		"observatoryWest"       => "grandBalcony",
		"observatorySouth"      => "mapRoom",
		"mapRoomNorth"          => "observatory",
		"grandBalconySouth"     => "corridor2fn",
		"corridor2fnNorth"      => "grandBalcony",
		"corridor2fnEast"       => "mapRoom",
		"mapRoomWest"           => "corridor2fn",
		"corridor2fnWest"       => "billiardsRoom",
		"billiardsRoomEast"     => "corridor2fn",
		"corridor2fsEast"       => "goldilocksRoom",
		"goldilocksRoomWest"    => "corridor2fs",
		"corridor2fsWest"       => "masterBedchambers",
		"masterBedchambersEast" => "corridor2fs",
		"masterBedchambersNorth"=> "gerderobe",
		"gerderobeSouth"        => "masterBedchambers",
		
//        "tapestryWSouth"       => "cloakRoom",             // i3
//        "pantryWest"           => "storage1",              // i3
//        "storage1East"         => "pantry",                // i3
//        "hallway1West"         => "infirmary",             // i3
	);
	
	
    $objectDescriptions = array(

        // Iteration 1 Object Descriptions:
        "rustyKey" => "It's a dingy rusty key.",
        "brassKey" => "It's a nice and shiny brass key.",
        "lambChop" => "It's a tasty looking lamb chop.",
        "dog" => "It's a sizeable looking dog is sitting by the northern door, watching you alertly.",
        "bowl" => "It's an empty bowl sitting on the floor.",
        "footLocker" => "It's a servant's simple footLocker chest that is sitting on the floor.",
        "lamp" => "It's an old brass lamp."
      
        // Iteration 2 Object Descriptions:
      
    );
	if(!isset($_SESSION['roomObjects']))
	{
		$_SESSION['roomObjects'] = array(
		
		  // Iteration 1 Room Objects:
		  "library" => "rustyKey",
		  "pantry" => "brassKey",
		  "kitchen" => "lambChop",
		  "taxidermyRoom" => "dog",
		  //"The dog growls at you menacingly, and will not let you pass by.";
		  "taxidermyRoom" => "bowl",
		  "servantsQuarters" => "footLocker",
		  "footLocker" => "lamp",  // This is an object inside an object
		
		 // Iteration 2 Room Objects:
		
		);
	}
    
	// User Items
	$usersItems = array(
		"backPack" => "no",
	);
	
	if(!isset($_SESSION['handsArray']))
	{
		// Hands Array
		$_SESSION['handsArray'] = array(
			"leftHand" => "",
			"rightHand" => "",
		);
	}


	// Back Pack Array
	$backPackArray = array(
		"itermOne" => "",
		"itermTwo" => "",
		"itermThree" => "",
		"itermFour" => "",
		"itermFive" => "",
		"itermSix" => "",
	);
	
	// Back Pack Array
	$newImages = array(
		"rustyKey" => "library.jpg",
		"lambChop" => "kitchen.jpg",
	);
	
	// Commands Array
	$commandsArray = array(
		"door.unlock(rightHand);" => "unlockDoor",
		"door.unlock(leftHand);" => "unlockDoor",
		"System.out.println(leftHand);" => "displayHand",
		"System.out.println(rightHand);" => "displayHand",
		"moveNorth();" => "moveCharacter",
		"north" => "moveCharacter",
		"n" => "moveCharacter",
		"moveSouth();" => "moveCharacter",
		"south" => "moveCharacter",
		"s" => "moveCharacter",
		"moveEast();" => "moveCharacter",
		"east" => "moveCharacter",
		"e" => "moveCharacter",
		"moveWest();" => "moveCharacter",
		"west" => "moveCharacter",
		"w" => "moveCharacter",
        "up" => "moveCharacter",
        "moveUp();" => "moveCharacter",
        "u" => "moveCharacter",
        "down" => "moveCharacter",
        "moveDown();" => "moveCharacter",
        "d" => "moveCharacter",
		"reset" => "resetGame",
		"leftHand =" => "assignToHand",
		"rightHand =" => "assignToHand",

	);
	
	// Short Hand Definition Array
	$definition = array(
		"moveNorth();" => "north",
		"north" => "north",
		"n" => "north",
		"moveSouth();" => "south",
		"south" => "south",
		"s" => "south",
		"moveEast();" => "east",
		"east" => "east",
		"e" => "east",
		"moveWest();" => "west",
		"west" => "west",
		"w" => "west",
        "moveUp();" => "up",
        "up" => "up",
        "u" => "up",
        "moveDown();" => "down",
        "down" => "down",
        "d" => "down",
	);
    
	if(!isset($_SESSION['obstacles']))
	{
		// obstacles Array
		$_SESSION['obstacles'] = array(
         "loungeEast" => "door",
		 "loungeEastKey" => "rustyKey",
         "taxidermyRoomNorth" => "dog",
         "banquetHallEast" => "door",
		);
	}
?>


