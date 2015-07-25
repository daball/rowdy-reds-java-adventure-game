<?php
	if(!isset($_POST['screen'])){
		$screen = 1;
	}
	else{
		$screen = $_POST['screen'];
	}

	if(isset($_POST['ans'])){
		$command = trim(substr($_POST['ans'], strrpos($_POST['ans'], '> ') + 1));
		include 'rules.php';
	}

	if(!isset($returnAnswer)){
		$returnAnswer = "> ";
	}

	$roomImages = Array
	(
			1 => "background.jpg",
			2 => "mainHall.jpg",
			3 => "castleRoom.jpg",
			20 => "exitScreen.jpg"
	);
?>

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
			<div id="screen" style="background-image: url('images/<?php echo $roomImages[$screen]; ?>'); background-size:100%; background-repeat: no-repeat;">
			</div>
			<?php
			if(!isset($consoleHidden))
			{
				?>

			<div id="holder">
				<form method="post" id="answerForm" name="answerForm">
					<input style="display: none;" type="text" name="screen" value="<?php echo $screen; ?>" />
					<textarea autofocus id="ans" name="ans" style="color: #eee; background-color: black; height: 535px;" onkeydown="if (event.keyCode == 13) {document.getElementById('button').click();} if (event.keyCode == 8 ) {var id = document.getElementById('ans').value; var check = id.substr(id.length - 2) + id.substr(id.length - 1); if(check == '>  '){ return false;} } if(event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 ) { return false;} "><?php
						if(isset($_POST['ans'])){
							echo $returnAnswer;
						}
						else{
						 echo "You are standing at a castle door.  A door lies to the north.
> ";
						}
					?></textarea>

					<input id="button" hidden type="submit" value="Enter Command" />
				</form>
			</div>
			<?php
			}
			?>
			<img style="margin-left: 230; float: left;" src="images/light.png" />
		</div>
	</body>
</html>
