<?php
	$breakLine = "&#13;&#10;> ";
	
	
	// Open Door With Java
	// Finish Help Menu
	// Exit
	
	// Any Screen
	if(strtolower(strtolower($command)) == "restart")
	{
		$screen = 1;
		$returnAnswer =  "> You restarted the game all over. You are now at the front door." . $breakLine;
	}
	else if(strtolower($command) == "help")
	{
		$returnAnswer =  $_POST['ans'] . $breakLine . "*** THE HELP WILL GO HERE ***" . $breakLine;
	}
	else if(strtolower($command) == "exit")
	{
		$screen = 20;
		$consoleHidden = "yes";
		$returnAnswer =  $_POST['ans'] . $breakLine . "*** THE HELP WILL GO HERE ***" . $breakLine;
	}
	else
	{
		if($screen == 1)// front door screen
		{ 
			if(strtolower($command) == "north" || strtolower($command) == "n")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot go north." . $breakLine;
			}
			else if(strtolower($command) == "south" || strtolower($command) == "s")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot go south." . $breakLine;
			}
			else if(strtolower($command) == "east" || strtolower($command) == "e")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot go east." . $breakLine;
			}
			else if(strtolower($command) == "west" || strtolower($command) == "w")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot go west." . $breakLine;
			}
			else if(strtolower($command) == "inspect")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "Greet the world on the console." . $breakLine;
			}
			else if($command == "Open Door")
			{
				// System.out.print("
				// ");
				$screen = 2;
				$returnAnswer =  $_POST['ans'] . $breakLine . "You are now in the main hall." . $breakLine;
			}
			else
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "I do not understand." . $breakLine;
			}
		}
		else if($screen == 2) // main hall screen
		{
			if(strtolower($command) == "north" || strtolower($command) == "n")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot do that." . $breakLine;
			}
			else if(strtolower($command) == "south" || strtolower($command) == "s")
			{
				$screen = 1;
				$returnAnswer =  $_POST['ans'] . $breakLine . "You are now at the front door." . $breakLine;
			}
			else if(strtolower($command) == "east" || strtolower($command) == "e")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot do that." . $breakLine;
			}
			else if(strtolower($command) == "west" || strtolower($command) == "w")
			{
				$returnAnswer =  $_POST['ans'] . $breakLine . "You cannot do that." . $breakLine;
			}
		}
	}
?>