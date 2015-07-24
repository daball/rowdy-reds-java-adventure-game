<?php
	$breakLine = "&#13;&#10;> ";
	$breakLineOne = "&#13;&#10;";
	// ARRAY ROOM MOVEMENTS
	//function moveMe($move, $room)
	//{
		//$roomOne = array("north" => "no", "south" => "no", "east" => "no", "west" => "no");
		
		//if()
		//{
			//return $returnAnswer =  $_POST['ans'] . $breakLine . "You cannot go" . $move . "." . $breakLine;
		//}
		//else
		//{
			
		//}
	//}
	
	// Any Screen
	if($command == "")
	{
		$returnAnswer = $_POST['ans'];
	}
	else if(strtolower(strtolower($command)) == "restart" || strtolower(strtolower($command)) == "reset")
	{
		$screen = 1;
		$returnAnswer =  "Game Restarted. You are standing at a castle door.  A door lies to the north." . $breakLine;
	}
	else if(strtolower($command) == "help")
	{
		$returnAnswer =  $_POST['ans'] . $breakLineOne . "*** Help Menu ***" . $breakLineOne
		. "Goal: Explore our tiny castle." . $breakLineOne . $breakLineOne
		. "HELP - print this help screen" . $breakLineOne . $breakLineOne
		
		. "Restart the game by typing:" . $breakLineOne
		. "restart or reset" . $breakLineOne . $breakLineOne
		
		. "Exit the game by typing:" . $breakLineOne
		. "exit or System.exit(0);" . $breakLineOne . $breakLineOne

		. "Move north with the following commands:" . $breakLineOne
		. "north, n, or moveNorth();" . $breakLineOne . $breakLineOne

		. "Move south with the following commands:" . $breakLineOne
		. "south, s, or moveSouth();" . $breakLineOne . $breakLineOne

		. "Move west with the following commands:" . $breakLineOne
		. "west, w, moveWest();" . $breakLineOne . $breakLineOne

		. "Move east with the following commands:" . $breakLineOne
		. "east, e, moveEast();" . $breakLine;
		
	}
	else if(strtolower($command) == "exit" || $command == "System.exit(0);")
	{
		$screen = 20;
		$consoleHidden = "yes";
		$returnAnswer =  "";
	}
	else
	{
		if($screen == 1)// front door screen
		{ 
			if(strtolower($command) == "north" || strtolower($command) == "n" || $command == "moveNorth();")
			{
				$screen = 2;
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You are in a lavishly decorated hallway.  The kitchen lies to the west, and the door to the outside is to the south." . $breakLine;
			}
			else if(strtolower($command) == "south" || strtolower($command) == "s" || $command == "moveSouth();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go south." . $breakLine;
			}
			else if(strtolower($command) == "east" || strtolower($command) == "e"  || $command == "moveEast();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go east." . $breakLine;
			}
			else if(strtolower($command) == "west" || strtolower($command) == "w" || $command == "moveWest();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go west." . $breakLine;
			}
			else
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "I do not understand." . $breakLine;
			}
		}
		else if($screen == 2) // main hall screen
		{
			if(strtolower($command) == "north" || strtolower($command) == "n" || $command == "moveNorth();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go north." . $breakLine;
			}
			else if(strtolower($command) == "south" || strtolower($command) == "s" || $command == "moveSouth();")
			{
				$screen = 1;
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You are standing at a castle door.  A door lies to the north." . $breakLine;
			}
			else if(strtolower($command) == "east" || strtolower($command) == "e"  || $command == "moveEast();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go east." . $breakLine;
			}
			else if(strtolower($command) == "west" || strtolower($command) == "w" || $command == "moveWest();")
			{
				$screen = 3;
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You are in a kitchen.  Someone has been cooking here lately and the smell of mutton still hangs heavy in the air.  The hallway lies to the east." . $breakLine;
			}
			else
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "I do not understand." . $breakLine;
			}
		}
		else if($screen == 3) // main hall screen
		{
			if(strtolower($command) == "north" || strtolower($command) == "n" || $command == "moveNorth();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go north." . $breakLine;
			}
			else if(strtolower($command) == "south" || strtolower($command) == "s" || $command == "moveSouth();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go south." . $breakLine;
			}
			else if(strtolower($command) == "east" || strtolower($command) == "e"  || $command == "moveEast();")
			{
				$screen = 2;
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You are in a lavishly decorated hallway.  The kitchen lies to the west, and the door to the outside is to the south." . $breakLine;
			}
			else if(strtolower($command) == "west" || strtolower($command) == "w" || $command == "moveWest();")
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "You cannot go west." . $breakLine;
			}
			else
			{
				$returnAnswer =  $_POST['ans'] . $breakLineOne . "What you talkin about Willis?" . $breakLine;
			}
		}
	}
?>