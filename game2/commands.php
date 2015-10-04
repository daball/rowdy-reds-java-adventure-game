<?php
	// Include Everything From Helper PHP
	include 'helper.php';
	
	// Set New Console Text
	$_SESSION['console'] = $_POST['commandHistory'];
	
	// Parse Command
	$command = trim(substr($_POST['commandHistory'], strrpos($_POST['commandHistory'], '>') + 1));
	
	// Process The Command
	runCommand($command, $commandsArray);
	
	// Go Back To The Main Page
	header("Location: index.php");
	exit;
?>