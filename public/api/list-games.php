<?php

require_once __DIR__.'/../../app/engine/GameEngine.php';

use \engine\GameEngine;

echo json_encode(GameEngine::getValidGames(), JSON_PRETTY_PRINT);
