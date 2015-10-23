<?php
	include 'helper.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Rowdy Red's Adventure</title>
		<link type="text/css" href="includes/style.css" rel="stylesheet" />
		<script type="text/javascript" src="includes/jquery.min.js"></script>
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
			
			var isD = false;
			function val(event){
				
				if(event.keyCode == 38)
				{
					if(!isD){
						event.preventDefault();
						// last command process 
						var last = "<?php echo $_SESSION['lastCommand'];?>";
						document.getElementById('commandHistory').value = document.getElementById('commandHistory').value + last;
						isD=true;
					
					}
					else{
						event.preventDefault();
					}

				}
				if(event.keyCode == 40)
				{
					event.preventDefault();
				}
				if (event.keyCode == 13) 
				{
					document.getElementById('button').click();
				}
				if (event.keyCode == 8 )
				{
					var id = document.getElementById('commandHistory').value;
					
					var check = id.substr(id.length - 1); 
					
					if(check == '>')
					{ 
						event.preventDefault();
					} 
				}
				if(event.keyCode == 37 || event.keyCode == 39){
						event.preventDefault();
				}
				if(event.keyCode == 46)
				{
					event.preventDefault();
				} 
				move();
				return false;
			}
		</script>
	</head>
	<body>
		<div class="container">
			<img class="light-left" src="images/light.png" />
			<div id="screen" style="background-image: url('images/<?php echo getJunk($_SESSION['CurrentRoom'], $_SESSION['roomImage']); ?>');"></div>
			<?php if(!isset($_SESSION['ExitGame'])){ ?>
				<div class="form-container">
					<form action="commands.php" method="post" id="answerForm" name="answerForm">
						<textarea autofocus id="commandHistory" name="commandHistory" style="color: #eee; background-color: black; height: 535px;" onkeydown="val(event)"><?php echo printConsole(); ?></textarea>
						<input id="button" hidden type="submit" value="Enter Command" />
					</form>
				</div>
			
			<?php } ?>
			<img class="light-right" src="images/light.png" />
		</div>
		<?php if(isset($_SESSION['showTablet']) && $_SESSION['showTablet'] == true){ ?>
			<form action="tablet.php" method="post" id="tablet" name="tablet">
				<textarea autofocus id="tabHistory" name="tabHistory" style="color: green; background-color: #eee; height: 335px; width: 1150px; margin-top: 20px; margin-left: 80px; margin-bottom: 30px;"></textarea>
				<input id="tabButton" hidden type="submit" value="Enter Command" />
			</form>
		<?php } ?>
	</body>
</html>