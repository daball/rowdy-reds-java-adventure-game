<?php

require_once("http://localhost:8081/cc-development/java/Java.inc");

$Player = new java("edu.radford.rowdyred.game.Player");
echo $Player->inspect();
echo $Player->inspect('something');
