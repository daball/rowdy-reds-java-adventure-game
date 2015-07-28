<?php

require_once __DIR__.'/app/GameEngine.php';

$gameEngine = new GameEngine();

//install shortcut variables
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
		<link href="./css/style.css" rel="stylesheet" type="text/css" />
		<script src="./vendor/components/jquery/jquery.min.js"></script>
		<script>
			$(function() {
			  $("[autofocus]").on("focus", function() {
				if (this.setSelectionRange) {
				  var len = this.value.length * 2;
				  this.setSelectionRange(len, len);
				} else {
				  this.value = this.value;
				}
				this.scrollTop = 999999;
			  }).focus();
			});
		</script>
	</head>
	<body>
		<div style="width: 1350px; height: auto; margin-top: 25px;">
			<img style="margin-left: 0px; float: left;" src="images/light.png" />
			<div id="screen" style="background-image: url('images/<?=$isExiting?'exitScreen.jpg':$imageUrl?>'); background-size:100%; background-repeat: no-repeat;">
			</div>
			<?php
			if(!$isExiting)
			{
				?>

			<div id="holder">
				<form method="post" id="answerForm" name="answerForm">
					<textarea autofocus id="commandLine" name="commandLine" style="color: #eee; background-color: black; height: 535px;" spellcheck="false" autocorrect="false" autocapitalize="false" onkeydown="if (event.keyCode == 13) {this.value = this.value.trim().split('\n').pop().substring(3); document.getElementById('button').click();} if (event.keyCode == 8 ) {var id = document.getElementById('commandLine').value; var check = id.substr(id.length - 2) + id.substr(id.length - 1); if(check == '>  '){ return false;} } if(event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 ) { return false;} "><?=
					$consoleHistory . $eol . '>  '?></textarea>

					<input id="button" hidden type="submit" value="Enter Command" />
				</form>
			</div>
			<?php
			}
			?>
			<img style="margin-left: 230; float: left;" src="images/light.png" />
		</div>
	</body>
</html><?php if ($isExiting) {
	//reset game state
	$gameEngine->gameState = new GameState();
	$gameEngine->saveSession();
}
