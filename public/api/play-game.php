<?php

require_once __DIR__.'/../../app/engine/GameEngine.php';
require_once __DIR__.'/../../app/util/PubSubMessageQueue.php';

use \engine\GameEngine;
use \util\PubSubMessageQueue;

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
$player = $gameState->getPlayer();

$avatarRoom = $gameState->getPlayerRoom();
$moves = $gameState->getMoves();
$isExiting = $gameState->isExiting();
$showManual = $gameState->showManual();

$gameName = $game->getName();
$roomName = $avatarRoom->getName();
$imageUrl = $avatarRoom->getImageUrl();

$leftHand = "";
if ($player->getLeftHand()->getComponent('Container')->hasItemAt(0))
	$leftHand = $player->getLeftHand()->getComponent('Container')->getItemAt(0)->getName();
$rightHand = "";
if ($player->getRightHand()->getComponent('Container')->hasItemAt(0))
	$rightHand = $player->getRightHand()->getComponent('Container')->getItemAt(0)->getName();
$equipment = $player->listEquipment();

$consoleHistory = $gameState->getConsoleHistory();
$commandHistory = $gameState->getCommandHistory();
$tabletCode = $gameState->getTabletCode();
$commandProcessor = $gameEngine->getCommandProcessor();
$commandInput = $commandProcessor->getCommandInput();
$commandOutput = $commandProcessor->getCommandOutput();
$logger = array();
PubSubMessageQueue::subscribe("Logger", function ($sender, $queue, $message) use (&$logger) {
	array_push($logger, $message);
});

$obviousDirections = array();
if ($avatarRoom->getDirection('u')->isNextRoomObvious()) array_push($obviousDirections, 'U');
if ($avatarRoom->getDirection('w')->isNextRoomObvious()) array_push($obviousDirections, 'W');
if ($avatarRoom->getDirection('n')->isNextRoomObvious()) array_push($obviousDirections, 'N');
if ($avatarRoom->getDirection('e')->isNextRoomObvious()) array_push($obviousDirections, 'E');
if ($avatarRoom->getDirection('s')->isNextRoomObvious()) array_push($obviousDirections, 'S');
if ($avatarRoom->getDirection('d')->isNextRoomObvious()) array_push($obviousDirections, 'D');

/* RENDER MODEL TO VIEW AND OUTPUT RESPONSE */

echo json_encode(array(
	'gameName' 					=> $gameName,
	'roomName' 					=> $roomName,
	'obviousDirections' => $obviousDirections,
	'imageUrl' 					=> $imageUrl,
  'consoleHistory' 		=> $consoleHistory,
  'commandHistory' 		=> $commandHistory,
	'player' 						=> array(
		'leftHand'					=> $leftHand,
		'rightHand'					=> $rightHand,
		'equipment' 				=> $equipment,
	),
	'eol' 						=> $eol,
  'prompt' 					=> $prompt,
	'moves' 					=> $moves,
	'isExiting' 			=> $isExiting,
	'showManual' 			=> $showManual,
	'tabletCode' 			=> $tabletCode,
	'logger' 					=> $logger,
), JSON_PRETTY_PRINT);

/* MAINTENANCE */

//when the game is exiting, go ahead and restart it, since there is no other way to restart the session
//next time it loads, it'll be ready to play
if ($gameEngine->getGameState()->isExiting()) {
	//unset isExiting for next time
	//$gameEngine->gameState = new GameState();
	$gameEngine->getGameState()->isExiting(false);
}
if ($gameEngine->getGameState()->showManual()) {
	//unset isExiting for next time
	//$gameEngine->gameState = new GameState();
	$gameEngine->getGameState()->showManual(false);
}

//always save the session state
$gameEngine->saveSession();
