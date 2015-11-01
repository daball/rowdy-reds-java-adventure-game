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
		
	function getState(){
		return false;
	}
	
	function saveState(){
		
	}
	
	function loadState(){
	}
	
	function exitAppend()
	{
		$isEast = getJunk($_SESSION['CurrentRoom'] . "East", $_SESSION['roomConnections']);
		$isWest = getJunk($_SESSION['CurrentRoom']. "West", $_SESSION['roomConnections']);
		$isSouth = getJunk($_SESSION['CurrentRoom']. "South", $_SESSION['roomConnections']);
		$isNorth = getJunk($_SESSION['CurrentRoom']. "North", $_SESSION['roomConnections']);
		
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
		
		consoleAppend($oExits . ".");
	}
	
	function inspect($command)
	{
		consoleAppend(getJunk($_SESSION['CurrentRoom'], $_SESSION['roomDescriptions']) . ".");
		exitAppend();
		objectAppend();
	}
	
	function inspectObject($object)
	{
		$desc = getJunk(trim($object), $_SESSION['objectDescriptions']);
		if(trim($desc) == "")
		{
			consoleAppend("That is not an object to inspect.");
		}
		else
		{
			consoleAppend($desc);
		}
		
	}
	
	function objectAppend()
	{
		$apendThis = "The objects are: ";
		$count = 0;
		
		foreach($_SESSION['roomObjects'] as $room => $object){
			if($room == $_SESSION['CurrentRoom'])
			{
				if(count($_SESSION['roomObjects'][$_SESSION['CurrentRoom']]) == 1)
				{
					$apendThis = $apendThis . "a " . $object . "";
				}
				else if($count == count($_SESSION['roomObjects']) - 1)
				{
					$apendThis = $apendThis . "and a " . $object . "";
				}
				else
				{
					$apendThis = $apendThis . "a " . $object . ", ";
				}
				$count = $count + 1;
			}
		}
		
		if($apendThis == "The objects are: ")
		{
			$apendThis = "You do not see any objects here";
		}
		
		consoleAppend($apendThis . ".");
	}
	
	function showTablet(){
		$_SESSION['showTablet'] = true;
	}
	
	function hideTablet(){
		$_SESSION['showTablet'] = false;
	}
	
	function startNewGame(){
		$_SESSION['CurrentRoom'] = "castleEntrance";
		$_SESSION['console'] = getJunk("castleEntrance", $_SESSION['roomDescriptions']);
		$_SESSION['tText'] = "// THERE IS NO JAVA CODE TO WRITE FOR THIS ROOM.";
		exitAppend();
		objectAppend();
	}
	
	// Run the Current Command That The User Input
	// Based on the Return Function of the Commands Array
	function runCommand($command)
	{
		// Remove any extra white spaces
		
		

		$arr = explode(' ',trim($command));
		
		if(isset($arr[1]) && $arr[0] == "inspect")
		{
			inspectObject($arr[1]);
		}
		else
		{
			$command = cleanCommand($command);
			$arr = explode(' ',trim($command));
			$checkThis = $arr[0];
			
			if(isset($arr[1]))
			{
				$checkThis = $checkThis . " " . $arr[1];
			}
			
			// Get the Function for the Command
			$returnCommand = getJunk($checkThis, $_SESSION['commandsArray']);
			
			// If the the Commands Array Returned a Function Run the Function
			if($returnCommand != ""){
				// Run Function Name The Array Return Us
				$returnCommand($command);
			}
			else {
				// Try to Get the Function for the Command again but in Lower Case
				$returnCommand = getJunk(strtolower($command), $_SESSION['commandsArray']);
				
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
	}
	
	function checkKey($whichHand, $direction)
	{
			$whatToGet = $_SESSION['CurrentRoom'] . $direction . "Key";
			
			$keyToUnlock = getJunk($whatToGet, $_SESSION['obstacles']);
			$itemInHand = getJunk($whichHand, $_SESSION['handsArray']);
			
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
		
		$isDoorEast = getJunk($_SESSION['CurrentRoom'] . "East", $_SESSION['obstacles']);
		$isDoorNorth = getJunk($_SESSION['CurrentRoom'] . "North", $_SESSION['obstacles']);
		$isDoorWest = getJunk($_SESSION['CurrentRoom'] . "West", $_SESSION['obstacles']);
		$isDoorSouth = getJunk($_SESSION['CurrentRoom'] . "South", $_SESSION['obstacles']);
		
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
	
	function dog(){
		consoleAppend("There is a dog blocking the way.");
	}
	
	function lambChop($first, $second){
		consoleAppend("The lambChop was dropped in the dog bowl and the dog is eating it.");
		
		// remove obstacle
		unset($_SESSION['obstacles'][$_SESSION['CurrentRoom'] . "North"]);
		unset($_SESSION['vObjects'][$first]);
		$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk("dog", $_SESSION['newImages']);
	}
	
	function assignToVariables($command)
	{
		$command = cleanCommand($command);
		
		$arr = explode(' ',trim($command));
		$first = $arr[0];
		$second = $arr[2];
		$val = getJunk($first, $_SESSION['vObjects']);
		$second = rtrim($second, ';');
		$second = $_SESSION['handsArray'][$second];
		
		if($second == $val)
		{
			// assign variable
			$second(strtolower($second));
		}
		else{
			consoleAppend("That doesn't go there!");
		}
	}
	
	function helpJunk()
	{
		$eol = "\n";
		consoleAppend("********* HELP MENU *********" . $eol
          . $eol
          . "Goal: Explore our tiny castle." . $eol
          . $eol
          . "New experimental commands:" . $eol
          . "  javadoc - list of known API classes in your javadoc notebook$eol"
          . "  javadoc ClassName - displays documentation about the API class$eol"
          . "  locals - displays list of variables is the player variable bag$eol"
          . "  gc - garbage collects a local variable the player has made$eol"
          . "  globals - displays list of variables available anywhere in the game$eol"
          . "  User can instantiate any class for which an API class has been found to a local variable.$eol"
          . $eol
          . "HELP displays this help screen." . $eol
          . $eol
          . "Restart the game by typing:" . $eol
          . "restart or reset" . $eol
          . $eol
          . "Exit the game by typing:" . $eol
          . "exit or System.exit(0);" . $eol
          . $eol
          . "Move north by typing:" . $eol
          . "north, n, or moveNorth();" . $eol
          . $eol
          . "Move south by typing:" . $eol
          . "south, s, or moveSouth();" . $eol
          . $eol
          . "Move west by typing:" . $eol
          . "west, w, moveWest();" . $eol
          . $eol
          . "Move east by typing:" . $eol
          . "east, e, moveEast();" . $eol
          . $eol
          . "********** END HELP **********");
	}
	
	
	
	function assignToHand($command)
	{
		
		$arr = explode(' ',trim($command));
		$checkThis = $arr[0];
		
		$semiThere = substr(trim($command), -1);
		
		if(count($arr) != 3 || $semiThere != ";")
		{
			consoleAppend("I do not understand.");
		}
		else
		{
			if(strtolower($arr[2]) == "bowl;")
			{
				consoleAppend("You cannot assign the bowl to your hand.");
			}
			else
			{
				$findInArray = str_replace(";","",$arr[2]);
				
				$isThere = getJunk($_SESSION['CurrentRoom'], $_SESSION['roomObjects']);
				
				if($isThere == $findInArray)
				{
					if($arr[0] == "leftHand")
					{
						consoleAppend("The " . $isThere . " has been assign to your left hand");
						$_SESSION['handsArray']['leftHand'] = $isThere;
						// Update Image					
						
						$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk($isThere, $_SESSION['newImages']);
						
						// Remove The item from room array
						unset($_SESSION['roomObjects'][$_SESSION['CurrentRoom']]);
					}
					else if($arr[0] == "rightHand")
					{
						consoleAppend("The " . $isThere . " has been assign to your right hand");
						$_SESSION['handsArray']['rightHand']  = $isThere;
						// Update Image
						$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk($isThere, $_SESSION['newImages']);
						
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
	
	function openLocker()
	{
		consoleAppend("You opened the foot locker with the brass key. There is now a lamp in the room!");		
		$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk("footLocker", $_SESSION['newImages']);
		unset($_SESSION['roomObjects'][$_SESSION['CurrentRoom']]);
		unset($_SESSION['roomObjects']['footLocker']);
		unset($_SESSION['commandsArray']['footLocker.unlock(leftHand);']);
		unset($_SESSION['commandsArray']['footLocker.unlock(rightHand);']);
		
		// ADD LAMP TO ROOM OBJECTS
		$_SESSION['roomObjects'][$_SESSION['CurrentRoom']] = "lamp";
	}
	
	function windLmap($command)
	{
		// leght the room;
		if($_SESSION['handsArray']['rightHand'] == "lamp" || $_SESSION['handsArray']['leftHand'] == "lamp")
		{
			if($_SESSION['CurrentRoom'] == "chessRoom")
			{
				$_SESSION['roomImage'][$_SESSION['CurrentRoom']] = getJunk("windLamp", $_SESSION['newImages']);
				consoleAppend("You are in the Chess Room.");
			}
				
			consoleAppend("The lamp is now on and you can see better.");
		}
		else
		{
			consoleAppend("I do not understand.");
		}
		
	}
	
	function unlockFootLocker($command)
	{
		
		if($_SESSION['CurrentRoom'] == "servantsQuarters")
		{
			if (strpos($command,'rightHand') !== false) {
				
				if($_SESSION['handsArray']['rightHand']  == ""){
					consoleAppend("You have nothing in your right hand.");
				}
				else if($_SESSION['handsArray']['rightHand'] == "brassKey")
				{
					openLocker();
				}
				else
				{
					consoleAppend("You cannot open the foot locker with a " . $_SESSION['handsArray']['rightHand']);
				}
			}
			else if (strpos($command,'leftHand') !== false) {
				if($_SESSION['handsArray']['leftHand']  == ""){
					consoleAppend("You have nothing in your left hand.");
				}
				else if($_SESSION['handsArray']['leftHand'] == "brassKey")
				{
					openLocker();
				}
				else
				{
					consoleAppend("You cannot open the foot locker with a " . $_SESSION['handsArray']['leftHand']);
				}
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
	
	// Try to Move Character Based on Current Room and Direction
	function moveCharacter($command)
	{
		$direction = getJunk($command, $_SESSION['definition']);
		$lookUp = $_SESSION['CurrentRoom'] . ucfirst($direction);
		
		
		$nextRoom = getJunk($lookUp, $_SESSION['roomConnections']);
		
		if($nextRoom != "")
		{
			// obstacle detection
			$obstacle = getJunk($lookUp, $_SESSION['obstacles']);
			if($obstacle != "")
			{
				consoleAppend("There's a $obstacle in the way!");
			}
			else
			{
				$_SESSION['CurrentRoom'] = $nextRoom;
				$_SESSION['tText'] = getJunk($_SESSION['CurrentRoom'], $_SESSION['tabText']);
				
				if(trim($_SESSION['tText']) == "")
				{
					$_SESSION['tText'] = "// THERE IS NO JAVA CODE TO WRITE FOR THIS ROOM.";
				}
				
				consoleAppend(getJunk($_SESSION['CurrentRoom'], $_SESSION['roomDescriptions']) . ".");
				exitAppend();
				objectAppend();
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
	
	function resetGame($command)
	{
		session_destroy();
		// Destroy Cookies
		header("Location: index.php");
		exit;
	}
?>