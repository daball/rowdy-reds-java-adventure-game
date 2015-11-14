<?php

require_once __DIR__.'/app/engine/GameEngine.php';

//start session services
session_start();

//start the game engine
$gameEngine = new \engine\GameEngine();

//install shortcut variables, for easier to read code
$eol = "\n";
$prompt = "> ";
// random comment
$gameState = $gameEngine->gameState;
$map = $gameState->map;

$avatarRoom = $gameState->getPlayerRoom();
$moves = $gameState->moves;
$isExiting = $gameState->isExiting;

$roomName = $avatarRoom->getName();
$imageUrl = $avatarRoom->getImageUrl();

$consoleHistory = $gameState->consoleHistory;
$commandProcessor = $gameEngine->commandProcessor;
$commandInput = $commandProcessor->commandInput;
$commandOutput = $commandProcessor->commandOutput;

$serialized = serialize($gameState);

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__.'/tmp',
));
$template = $twig->loadTemplate('index.html');
echo $template->render(array(
	'gameState' => $gameState,
	'map' => $map,
	'avatarRoom' => $avatarRoom,
	'moves' => $moves,
	'isExiting' => $isExiting,
	'roomName' => $roomName,
	'imageUrl' => $imageUrl,
	'consoleHistory' => $consoleHistory,
	'eol' => $eol,
  'prompt' => $prompt,
  'serialized' => $serialized,
));

//when the game is exiting, go ahead and restart it, since there is no other way to restart the session
//next time it loads, it'll be ready to play
if ($isExiting) {
	//reset game state
	$gameEngine->gameState = new GameState();
}

//always save the session state
$gameEngine->saveSession();
