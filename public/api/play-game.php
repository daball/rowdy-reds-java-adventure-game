<?php

use \engine\GameEngine;

function exception_error_handler($severity, $message, $file, $line) {
	// if (!(error_reporting() & $severity))
	// 	return;
	throw new ErrorException($message, 0, $severity, $file, $line);
}

try {

	set_error_handler("exception_error_handler");

	require_once __DIR__.'/../../app/engine/GameEngine.php';

	/* CONFIGURATION */
	$gameName = "Iteration 1"; //default
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['gameName']))
		$gameName = $_GET['gameName'];
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gameName']))
		$gameName = $_GET['gameName'];
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' && strstr($_SERVER['HTTP_ACCEPT'], 'application/json') !== FALSE) {
		$data = json_decode(file_get_contents('php://input'), true);
		if (isset($data['gameName']))
			$gameName = $data['gameName'];
	}

	//start session services
	session_start();

	/* OBTAIN VIEW MODEL */

	//start the game engine
	$gameEngine = new GameEngine($gameName);

	//install shortcut variables, for easier to read code
	$eol = "\n";
	$prompt = "> ";
	// random comment
	$gameState = $gameEngine->getGameState();
	$game = $gameState->getGame();

	$avatarRoom = $gameState->getPlayerRoom();
	$moves = $gameState->getMoves();
	$isExiting = $gameState->isExiting();

	$roomName = $avatarRoom->getName();
	$imageUrl = $avatarRoom->getImageUrl();

	$consoleHistory = $gameState->getConsoleHistory();
	$commandHistory = $gameState->getCommandHistory();
	$commandProcessor = $gameEngine->getCommandProcessor();
	$commandInput = $commandProcessor->getCommandInput();
	$commandOutput = $commandProcessor->getCommandOutput();

	/* RENDER MODEL TO VIEW AND OUTPUT RESPONSE */

	echo json_encode(array(
		'roomName' => $roomName,
		'imageUrl' => $imageUrl,
	  'consoleHistory' => $consoleHistory,
	  'commandHistory' => $commandHistory,
		'eol' => $eol,
	  'prompt' => $prompt,
		'moves' => $moves,
		'isExiting' => $isExiting,
	), JSON_PRETTY_PRINT);

	/* MAINTENANCE */

	//when the game is exiting, go ahead and restart it, since there is no other way to restart the session
	//next time it loads, it'll be ready to play
	if ($isExiting) {
		//reset game state
		$gameEngine->gameState = new GameState();
	}

	//always save the session state
	$gameEngine->saveSession();

}
catch (Exception $e) {
	echo json_encode(array(
		'error' => $e
	));
}
