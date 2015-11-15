<?php

require_once __DIR__.'/app/engine/GameEngine.php';

use \engine\GameEngine;

/* CONFIGURATION */

//start session services
session_start();

/* OBTAIN VIEW MODEL */

//start the game engine
$gameEngine = new GameEngine("Iteration1");

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
$commandProcessor = $gameEngine->getCommandProcessor();
$commandInput = $commandProcessor->getCommandInput();
$commandOutput = $commandProcessor->getCommandOutput();

/* OBTAIN VIEW */

Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__.'/tmp',
));
$template = $twig->loadTemplate('index.html');

/* RENDER MODEL TO VIEW AND OUTPUT RESPONSE */

echo $template->render(array(
	'gameState' => $gameState,
	'game' => $game,
	'avatarRoom' => $avatarRoom,
	'moves' => $moves,
	'isExiting' => $isExiting,
	'roomName' => $roomName,
	'imageUrl' => $imageUrl,
	'consoleHistory' => $consoleHistory,
	'eol' => $eol,
  'prompt' => $prompt,
));

/* MAINTENANCE */

//when the game is exiting, go ahead and restart it, since there is no other way to restart the session
//next time it loads, it'll be ready to play
if ($isExiting) {
	//reset game state
	$gameEngine->gameState = new GameState();
}

//always save the session state
$gameEngine->saveSession();
