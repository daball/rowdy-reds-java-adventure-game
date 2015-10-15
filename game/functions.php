<?php
	// Print Out All The Console
	function printConsole(){
		$_SESSION['console'] = $_SESSION['console'] . "\n" . ">";
		return $_SESSION['console'];
	}
	
	// Get Something Out Of An Array
	function getJunk($name, $myArray){
		$return = "";
		foreach ($myArray as $key => $value) {
			if($name == $key)
			{
				$return = $value;
			}
		}
		return $return; 
	}
	
	function getElement($name, $myArray){
		if (array_key_exists($name, $myArray))
		{
			return $myArray[$name];
		}
		return "";
		
	}
	function getState(){
		return false;
	}
	
	function saveState(){
		
	}
	
	function loadState(){
	}
	
	function exitAppend()
	{
		global $roomConnections;
		$isEast = getElement($_SESSION['CurrentRoom'] . "East", $roomConnections);
		$isWest = getElement($_SESSION['CurrentRoom']. "West", $roomConnections);
		$isSouth = getElement($_SESSION['CurrentRoom']. "South", $roomConnections);
		$isNorth = getElement($_SESSION['CurrentRoom']. "North", $roomConnections);
		
		$oExits = "The obvious exits are ";
		
		if ($isEast != "") {
			$oExits = $oExits . "east, ";
		}
		if ($isWest != "") {
			$oExits = $oExits . "west, ";
		}
		if ($isSouth != "") {
			$oExits = $oExits . "south, ";
		}
		if ($isNorth != "") {
			$oExits = $oExits . "north, ";
		}
		
		$oExits = substr($oExits, 0, -2);
		$pieces = explode(" ", $oExits);
		$oExits = "";
		
		for($i = 0; $i <= count($pieces); $i++) {
			if($i == (count($pieces) - 1) && count($pieces) > 5)
			{
				$oExits = $oExits . "and " . $pieces[$i];
			}
			else
			{
				if(isset($pieces[$i]))
				{
					$oExits = $oExits . $pieces[$i] . " ";
				}
				
			}
		}
		
		
		consoleAppend($oExits);
	}
	
	// Start New Game
	function startNewGame($roomDescriptions){
		// Set Current Room To The Entrance
		$_SESSION['CurrentRoom'] = "castleEntrance";
		
		// Set The Console Text
		$_SESSION['console'] = getElement("castleEntrance", $roomDescriptions);
		exitAppend();
	}
	
	// Run the Current Command That The User Input
	// Based on the Return Function of the Commands Array
	function runCommand($command, $commandsArray)
	{
		// Remove any extra white spaces
		$command = cleanCommand($command);
		
		$arr = explode(' ',trim($command));
		$checkThis = $arr[0];
		if(isset($arr[1]))
		{
			$checkThis = $checkThis + " " + $arr[1];
		}
				
		// Get the Function for the Command
		$returnCommand = getElement($checkThis, $commandsArray);

		// If the the Commands Array Returned a Function Run the Function
		if($returnCommand != ""){
			// Run Function Name The Array Return Us
			$returnCommand($command);
		}
		else {
			// Try to Get the Function for the Command again but in Lower Case
			$returnCommand = getElement(strtolower($command), $commandsArray);
			
			// If the the Commands Array Returned a Function Run the Function
			if($returnCommand != ""){
				// Run Function Name The Array Return Us
				$returnCommand(strtolower($command));
			}
			else {
				// Append Console Text With I Do Not Understand
				$_SESSION['console'] = $_SESSION['console'] . "\n" . "I do not understand.";
			}
		}
	}
	
	function checkKey($whichHand, $direction)
	{
			$whatToGet = $_SESSION['CurrentRoom'] . $direction . "Key";
			
			$keyToUnlock = getElement($whatToGet, $_SESSION['obstacles']);
			$itemInHand = getElement($whichHand, $_SESSION['handsArray']);
			
			if($keyToUnlock == $itemInHand)
			{
				$unsetOne = $_SESSION['CurrentRoom'] . $direction;
				$unsetTwo = $_SESSION['CurrentRoom']  . $direction . "Key";
			
				unset($_SESSION['obstacles'][$unsetOne]);
				unset($_SESSION['obstacles'][$unsetTwo]);
				
				consoleAppend("The door to the " . $direction . " was unlocked and opened with the " . $keyToUnlock . ".");
			}
			else
			{
				consoleAppend("You do not have the key for the door in that hand.");
			}
	}
	
	function unlockDoor($command)
	{
		preg_match('#\((.*?)\)#', $command, $match);
		$whichHand = $match[1];
		
		$isDoorEast = getElement($_SESSION['CurrentRoom'] . "East", $_SESSION['obstacles']);
		$isDoorNorth = getElement($_SESSION['CurrentRoom'] . "North", $_SESSION['obstacles']);
		$isDoorWest = getElement($_SESSION['CurrentRoom'] . "West", $_SESSION['obstacles']);
		$isDoorSouth = getElement($_SESSION['CurrentRoom'] . "South", $_SESSION['obstacles']);
		
		if($isDoorEast == "door")
		{
			checkKey($whichHand, "East");
		}
		if($isDoorSouth == "door")
		{
			checkKey($whichHand, "South");
		}
		if($isDoorNorth == "door")
		{
			checkKey($whichHand, "North");
		}
		if($isDoorWest == "door")
		{
			checkKey($whichHand, "West");
		}
		if($isDoorEast != "door" && $isDoorNorth != "door" && $isDoorWest != "door" && $isDoorSouth != "door")
		{
			consoleAppend("There is no door to unlock.");
		}
	}
	
	function assignToHand($command)
	{
		global $newImages;
		
		$arr = explode(' ',trim($command));
		$checkThis = $arr[0];
		
		$semiThere = substr(trim($command), -1);
		
		if(count($arr) != 3 || $semiThere != ";")
		{
			consoleAppend("I do not understand.");
		}
		else
		{
			$findInArray = str_replace(";","",$arr[2]);
			
			$isThere = getElement($_SESSION['CurrentRoom'], $_SESSION['roomObjects']);
			
			if($isThere == $findInArray)
			{
				if($arr[0] == "leftHand")
				{
					consoleAppend("The " . $isThere . " has been assign to your left hand");
					$_SESSION['handsArray']['leftHand'] = $isThere;
					// Update Image					
					
					$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk($isThere, $newImages);
					
					// Remove The item from room array
					unset($_SESSION['roomObjects'][$_SESSION['CurrentRoom']]);
				}
				else if($arr[0] == "rightHand")
				{
					consoleAppend("The " . $isThere . " has been assign to your right hand");
					$_SESSION['handsArray']['rightHand']  = $isThere;
					// Update Image
					$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk($isThere, $newImages);
					
					// Remove The item from room array
					unset($_SESSION['roomObjects'][$_SESSION['CurrentRoom']]);
				}
				else
				{
					consoleAppend("I do not understand.");
				}
				
			}
			else
			{
				consoleAppend("I do not understand.");
			}
		}
	}
	
	function displayHand($command)
	{
		if($command == "System.out.println(rightHand);")
		{
			if($_SESSION['handsArray']['rightHand']  == "")
			{
				consoleAppend("You have nothing in your right hand.");
			}
			else
			{
				consoleAppend("You have a " . $_SESSION['handsArray']['rightHand']  . " in your right hand.");
			}
			
		}
		else if($command == "System.out.println(leftHand);")
		{
			if($_SESSION['handsArray']['leftHand']  == "")
			{
				consoleAppend("You have nothing in your left hand.");
			}
			else
			{
				consoleAppend("You have a " . $_SESSION['handsArray']['leftHand']  . " in your left hand.");
			}
		}
	}
	
	// Try to Move Character Based on Current Room and Direction
	function moveCharacter($command)
	{
		global $definition;
		global $roomConnections;
		global $roomDescriptions;
		
		$direction = getElement($command, $definition);
		$lookUp = $_SESSION['CurrentRoom'] . ucfirst($direction);
		
		echo $lookUp;
		
		$nextRoom = getElement($lookUp, $roomConnections);
		
		if($nextRoom != "")
		{
			// obstacle detection
			$obstacle = getElement($lookUp, $_SESSION['obstacles']);
			if($obstacle != "")
			{
				consoleAppend("There's a $obstacle in the way!");
			}
			else
			{
				$_SESSION['CurrentRoom'] = $nextRoom;
				consoleAppend(getElement($_SESSION['CurrentRoom'], $roomDescriptions) . ".");
				exitAppend();
			}
		}
		else
		{
			consoleAppend("You cannot go $direction.");
		}
	}
	
	function cleanCommand($command)
	{
		$command = preg_replace('/( )+/', ' ', $command);
		$newCommand = "";
		$command = str_replace(" ;",";",$command);
		$strlen = strlen($command);
		
		for($i = 0; $i <= $strlen; $i++) {
			$char = substr($command, $i, 1);
			
			if($char == "=")
			{
				$newCommand = $newCommand . " " . $char . " ";
			}
			else
			{
				$newCommand = $newCommand . $char;
			}
		}
		$newCommand = preg_replace('/( )+/', ' ', $newCommand);
		return $newCommand;
	}
	
	function consoleAppend($text)
	{
		$_SESSION['console'] .= "\n" . $text;
	}

	// Reset The Game To Start Point
	function resetGame($command)
	{
		Global $roomDescriptions;
		session_destroy();
		session_start();
		startNewGame($roomDescriptions);
	}
?>