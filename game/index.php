<?php
	include 'helper.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Rowdy Red's Adventure</title>
		<link type="text/css" href="includes/style.css" rel="stylesheet" />
		<script type="text/javascript" src="includes/jquery.min.js"></script>
		<script type="text/javascript" src="includes/game.js"></script>
	</head>
	<body>
		<div class="container">
			<img class="light-left" src="images/light.png" />
			<div id="screen" style="background-image: url('images/<?php echo getJunk($_SESSION['CurrentRoom'], $_SESSION['roomImage']); ?>');"></div>
			<?php if(!isset($_SESSION['ExitGame'])){ ?>
				<div class="form-container">
					<form method="Post" id="answerForm" name="answerForm" action="commands.php">
						<textarea autofocus name="commandHistory" id="commandHistory" spellcheck="false" autocorrect="false" autocapitalize="false"><?php echo printConsole(); ?></textarea>
						<input id="button" hidden type="submit" value="Enter Command" />
					</form>
				</div>
			<?php } ?>
			<img class="light-right" src="images/light.png" />
		</div>
	</body>
</html>