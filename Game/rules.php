<?php	
	// Room Actions and Items
	$rooms = Array
	(
		Array // Start of Room Text
		(
			1 => "You are standing at a castle door. A door lies to the north.",
			2 => "You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.",
			3 => "You are in a kitchen. Someone has been cooking here lately and the smell of mutton still hangs heavy in the air. The hallway lies to the east."
		),
		Array // Room Movements
		(
			Array("n" => "2"), // Room 1
			Array("s" => "1", "w" => "3"), // Room 2
			Array("e" => "2")  // Room 3
		)
	);
	
	function lineBreak($breaks, $returnSymbol){
		if($returnSymbol == 1){
			$breakLine = "&#13;&#10;> ";
		}
		else{
			$breakLine = "&#13;&#10;";
		}
		
		$breakPoint = "";
		
		for ($k = 0 ; $k < $breaks; $k++){
			$breakPoint = $breakPoint . $breakLine;
		}

		return $breakPoint;
	}
	
	function moveMe($move, $room, $rooms){
		global $returnAnswer, $screen;
		if(isset($rooms[1][$room - 1][$move])){
			$screen = $rooms[1][$room - 1][$move];
			$returnAnswer =  $_POST['ans'] . lineBreak(1, 0) .  $rooms[0][$screen] . lineBreak(1, 1);
		}
		else{
			$subWords = array("n" => "north", "s" => "south", "e" => "east", "w" => "west");
			$returnAnswer =  $_POST['ans'] . lineBreak(1, 0) . "You cannot go " . $subWords[$move] . "." . lineBreak(1, 1);
		}
	}
	
	$movements = array("north", "n", "south", "s", "west", "w", "east", "e");
	$movementsCase = array("moveNorth();", "moveSouth();", "moveWest();", "moveEast();");
		
	if(in_array(strtolower($command), $movements)){
		moveMe(strtolower($command[0]), $_POST['screen'], $rooms);
	}
	else if(in_array($command, $movementsCase, true)){
		moveMe(strtolower($command[4]), $_POST['screen'], $rooms);
	}
	else if($command == ""){
		$returnAnswer = $_POST['ans'];
	}
	else if(strtolower(strtolower($command)) == "restart" || strtolower(strtolower($command)) == "reset"){
		$screen = 1;
		$returnAnswer =  "Game Restarted. You are standing at a castle door.  A door lies to the north." . lineBreak(1, 1);
	}
	else if(strtolower($command) == "help"){
		$returnAnswer =  $_POST['ans'] . lineBreak(5, 0). "********* HELP MENU *********" . lineBreak(2, 0)
		. "Goal: Explore our tiny castle." . lineBreak(2, 0)
		. "HELP - print this help screen" . lineBreak(2, 0)
		. "Restart the game by typing:" . lineBreak(1, 0)
		. "restart or reset" . lineBreak(2, 0)
		. "Exit the game by typing:" . lineBreak(1, 0)
		. "exit or System.exit(0);" . lineBreak(2, 0)
		. "Move north with the following commands:" . lineBreak(1, 0)
		. "north, n, or moveNorth();" . lineBreak(2, 0)
		. "Move south with the following commands:" . lineBreak(1, 0)
		. "south, s, or moveSouth();" . lineBreak(2, 0)
		. "Move west with the following commands:" . lineBreak(1, 0)
		. "west, w, moveWest();" . lineBreak(2, 0)
		. "Move east with the following commands:" . lineBreak(1, 0)
		. "east, e, moveEast();" . lineBreak(2, 0) . "********** END HELP **********" .  lineBreak(1, 1);
	}
	else if(strtolower($command) == "exit" || $command == "System.exit(0);"){
		$screen = 20;
		$consoleHidden = "yes";
		$returnAnswer =  "";
	}
	else{
		$returnAnswer =  $_POST['ans'] . lineBreak(1, 0) . "I do not understand." .  lineBreak(1, 1);
	}
?>