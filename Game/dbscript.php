<?php
	if(!defined('HOST_STUNNEL')){ // Check If A Connection Does Not Exist
		define('HOST_STUNNEL', '127.0.0.1'); // Define The Server
		define('DB_HOST', HOST_STUNNEL); // Define The Host With The Server
		define('DB_USER', 'root'); // Define The Host User Name
		define('DB_PASS', ''); // Define The Host Password
		define('DB_NAME', 'game'); // Define The Host Database Name
	}
	
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); // Make Connection To Database With Above Credentials
?>