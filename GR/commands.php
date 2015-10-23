<?php
	include 'helper.php';
	
	$_SESSION['console'] = $_POST['commandHistory'];
	$command = trim(substr($_POST['commandHistory'], strrpos($_POST['commandHistory'], '>') + 1));
	runCommand($command);
	$_SESSION['lastCommand'] = $command;
	header("Location: main.php");
	exit;
?>