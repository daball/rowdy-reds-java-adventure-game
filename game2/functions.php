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
	
	// Start New Game
	function startNewGame($roomDescriptions){
		// Set Current Room To The Entrance
		$_SESSION['CurrentRoom'] = "castleEntrance";
		
		// Set The Console Text
		$_SESSION['console'] = getJunk("castleEntrance", $roomDescriptions);
	}
	
	// Run the Current Command That The User Input
	// Based on the Return Function of the Commands Array
	function runCommand($command, $commandsArray)
	{
		// Get the Function for the Command
		$returnCommand = getJunk($command, $commandsArray);
		
		// If the the Commands Array Returned a Function Run the Function
		if($returnCommand != ""){
			// Run Function Name The Array Return Us
			$returnCommand($command);
		}
		else {
			// Try to Get the Function for the Command again but in Lower Case
			$returnCommand = getJunk(strtolower($command), $commandsArray);
			
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
	
	// Try to Move Character Based on Current Room and Direction
	function moveCharacter($command)
	{
		global $definition;
		global $roomConnections;
		global $roomDescriptions;
		
		$direction = getJunk($command, $definition);
		$lookUp = $_SESSION['CurrentRoom'] . ucfirst($direction);
		
		echo $lookUp;
		
		$nextRoom = getJunk($lookUp, $roomConnections);
		
		if($nextRoom != "")
		{
			$_SESSION['CurrentRoom'] = $nextRoom;
			$_SESSION['console'] = $_SESSION['console'] . "\n" . getJunk($_SESSION['CurrentRoom'], $roomDescriptions) . ".";
		}
		else
		{
			$_SESSION['console'] = $_SESSION['console'] . "\n" . "You cannot go " . $direction;
		}
	}

	// Reset The Game To Start Point
	function resetGame($command)
	{
		startNewGame();
	}
?>