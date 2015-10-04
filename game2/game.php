<?php
	// Room Descriptions Array
	$roomDescriptions = array(
		  "forest" => "You are standing in a forest.  There are trees all around you.  A path leads north.",
		  "castleEntrance" => "You are at the edge of a forest and are standing at a grand castle.  The castle's door lies to the north.",
		  "foyer" => "You are in the castle foyer.",
		  "tapestryE" => "You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.",
		  "tapestryW" => "You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.",
		  "study" => "You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.",
		  "library" => "You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.",
		  "conservatory" => "You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.",
		  "lounge" => "You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.",
		  "butlersQuarters" => "You are in the butler's quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!",
		  "kitchen" => "You are in the kitchen.  The smell of freshly cooked meat still lingers heavily in the air.",
		  "pantry" => "You descend down some stairs into in the kitchen pantry.  The pantry is stocked with many dry goods.",
		  "banquetHall" => "You are in the banquet hall.",
		  "hallwayS" => "You are in the south end of a hallway.",
		  "hallwayN" => "You are in the north end of a hallway.",
		  "servantsQuarters" => "You are in a humble servant's quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.",
		  "taxidermyRoom" => "You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he's watching you intently.  A bowl also sits on the floor nearby.",
		  "chessRoom" => "This room is pitch black.  You can't see anything." ,
	);
	
	// Room Image Array
	$roomImage = array(
		  "forest" => "null.png",
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
		  "hallwayS" => "null.png",
		  "hallwayN" => "null.png",
		  "servantsQuarters" => "servantsQuarters.jpg",
		  "taxidermyRoom" => "taxidermyRoom_dog.jpg",
		  "darkRoom" => "darkRoom.jpg",
	);
	
	// Directions Array
	$roomConnections = array(
		"castleEntranceNorth" => "foyer",
		"castleEntranceSouth" => "forest",
		"forestNorth" => "castleEntrance",
		
		"foyerSouth" => "castleEntrance",
		"foyerNorth" => "tapestryE",
		"foyerEast" => "conservatory",
		
		
	
	);
	
	// Room Objects
	$roomObjects = array(
		"entrance" => "rustyKey",
	);
	
	// User Items
	$usersItems = array(
		"backPack" => "no",
	);
	
	// Hands Array
	$handsArray = array(
		"leftHand" => "",
		"rightHand" => "",
	);

	// Back Pack Array
	$backPackArray = array(
		"itermOne" => "",
		"itermTwo" => "",
		"itermThree" => "",
		"itermFour" => "",
		"itermFive" => "",
		"itermSix" => "",
	);
	
	// Commands Array
	$commandsArray = array(
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
		"reset" => "resetGame",
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
	);
?>