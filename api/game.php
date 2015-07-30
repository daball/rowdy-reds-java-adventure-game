<?php

require_once __DIR__.'/app/GameEngine.php';

//start session services
session_start();

//turn JSON post { 'commandLine': '' } into $_POST['commandLine']
$jsonPost = json_decode(file_get_contents('php://input'));
foreach ($jsonPost as $k=>$v)
{
  $_POST[$k] = $v;
}

//start the game engine
$gameEngine = new GameEngine();

$gameState = $gameEngine->gameState;
$commandProcessor = $gameEngine->commandProcessor;
$commandInput = $commandProcessor->commandInput;
$commandOutput = $commandProcessor->commandOutput;

echo json_encode($gameEngine);

if ($gameEngine->gameState->isExiting) {
	//reset game state
	$gameEngine->gameState = new GameState();
}

//always save the session state
$gameEngine->saveSession();
