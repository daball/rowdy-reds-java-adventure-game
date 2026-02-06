<?php

require_once("http://tomcat:8080/cc-development/java/Java.inc");

$Player = new java("edu.radford.rowdyred.game.Player");
echo $Player->inspect();
echo $Player->inspect('something');
