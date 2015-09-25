<?php

use \java\JavaReflection;

require_once 'JavaReflection.php';
require_once __DIR__.'/../player/Assignable.php';
require_once __DIR__.'/../player/System.php';
require_once __DIR__.'/../player/Player.php';
require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../map/Direction.php';

echo JavaReflection::javadoc('GameState');
echo JavaReflection::javadoc('System');
echo JavaReflection::javadoc('PrintStream');
echo JavaReflection::javadoc('OutputStream');
echo JavaReflection::javadoc('Player');
echo JavaReflection::javadoc('Assignable');
