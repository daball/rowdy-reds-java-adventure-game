<?php
	include 'helper.php';

	$_SESSION['tText'] = $_POST['tabHistory'];
	$command = trim($_SESSION['tText']);

	function runCode($javaCode)
	{
		if($_SESSION['CurrentRoom'] == "goldilocksRoom")
		{
			$wayOne = 'if(bed.equals("hard")){fluffPillow=true;}elseif(bed.equals("soft")){eatCookies=true;}else{bed="justright";}';
			$wayTwo = 'if(bed.equals("soft")){eatCookies=true;}elseif(bed.equals("hard")){fluffPillow=true;}else{bed="justright";}';


			$javaCode = str_replace(' ', '', $javaCode);
			$javaCode = preg_replace( "/\r|\n/", "", $javaCode);

			if(strpos($javaCode, $wayOne) !== false || strpos($javaCode, $wayTwo) !== false){
				consoleAppend("You wrote the correct Java Code and made it past the goldilocksRoom");
			}
			else{
				consoleAppend("Error in code try again.");
			}
		}
		else {
			//need some output for any other condition
			consoleAppend("You wrote some Java code in the tablet and nothing happened.");
		}
	}

	runCode($command);

	// $_SESSION['lastCommand'] = $command;
	header("Location: main.php");
	exit;
?>
