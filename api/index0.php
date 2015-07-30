<?php

require_once __DIR__.'/app/GameEngine.php';

//start session services
session_start();

//start the game engine
$gameEngine = new GameEngine();

//install shortcut variables, for easier to read code
$eol = "\n";

$gameState = $gameEngine->gameState;
$map = $gameState->map;

$avatarRoom = $gameState->getAvatarRoom();
$moves = $gameState->moves;
$isExiting = $gameState->isExiting;

$roomName = $avatarRoom->name;
$imageUrl = $avatarRoom->imageUrl;

$consoleHistory = $gameState->consoleHistory;
$commandProcessor = $gameEngine->commandProcessor;
$commandInput = $commandProcessor->commandInput;
$commandOutput = $commandProcessor->commandOutput;

?><!DOCTYPE html>
<html>
	<head>
		<title>Rowdy Red's Java Adventure</title>
		<link type="text/css" href="./css/style.css" rel="stylesheet" />
		<script type="text/javascript" src="./vendor/components/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="./js/game.js"></script>
	</head>
	<body>
		<div class="container">
			<img class="light-left" src="./images/light.png" />
			<div id="screen" style="background-image: url('images/<?=$isExiting?'exitScreen.jpg':$imageUrl?>');"></div>
			<?php if (!$isExiting) { ?>
				<div class="form-container">
					<form method="post" id="answerForm" name="answerForm">
						<input type="hidden" id="commandLine" name="commandLine" value="" />
						<textarea autofocus id="commandHistory" spellcheck="false" autocorrect="false" autocapitalize="false"><?=$consoleHistory . $eol . '> '?></textarea>
						<input id="button" hidden type="submit" value="Enter Command" />
					</form>
				</div>
			<?php } ?>
			<img class="light-right" src="./images/light.png" />
		</div>
	</body>
</html><?php

//when the game is exiting, go ahead and restart it, since there is no other way to restart the session
//next time it loads, it'll be ready to play
if ($isExiting) {
	//reset game state
	$gameEngine->gameState = new GameState();
}

//always save the session state
$gameEngine->saveSession();
