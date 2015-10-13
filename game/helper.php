<?php

	if(!isset($_SESSION)) // If Session Is Not Started Then Start It
	{
		session_start();
	}
	include 'game.php';
	include 'functions.php';
	if(isset($_SESSION['Hold'])) // If HoldState Is Set Then Save the State
	{
		saveState();
	}
	else if(getState()) // If GetState Is Set Then Load the State
	{
		loadState();
		$_SESSION['Hold'] = "YES";
	}
	else // Start A New State
	{
		startNewGame($roomDescriptions);
		saveState();
		$_SESSION['Hold'] = "YES";
	}
?>