<?php

namespace engine\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/engine/GameEngine.php';
require_once __DIR__.'/../../../../app/engine/GameState.php';

use \engine\GameEngine;
use \engine\GameState;

class GameStateTest extends \PHPUnit_Framework_TestCase
{
  public function testGameState()
  {
    $game = GameEngine::loadGame('Iteration0');
    $gameState = GameState::getInstance();

    $this->assertEquals($gameState->getGame()->getSpawnPoint(), $gameState->getPlayerRoom()->getName());
    $this->assertEquals($gameState->getGame()->getRoom($gameState->getGame()->getSpawnPoint())->getName(), $gameState->getPlayerRoom()->getName());
    $this->assertContains("Game started.", $gameState->getConsoleHistory());
    $this->assertContains($gameState->getPlayerRoom()->inspectRoom(), $gameState->getConsoleHistory());

    //test inspect room
    $this->assertEquals($gameState->getPlayerRoom()->inspectRoom(), $gameState->getGame()->getRoom($gameState->getPlayer()->getLocation())->getComponent('Inspector')->inspect());
    $this->assertEquals($gameState->getPlayer()->getLocation(), $gameState->getPlayerRoom()->getName());
    $this->assertEquals($gameState->getPlayerRoom()->inspectRoom(), $gameState->getPlayerRoom()->getComponent('Inspector')->inspect());

    //move the character's location, one room to the north
    $initialRoom = $gameState->getPlayer()->getLocation();
    $gameState->getPlayer()->navigate('north');
    $this->assertNotEquals($initialRoom, $gameState->getPlayer()->getLocation());

    //reset the game state
    $gameState->resetGameState();
    $this->assertEquals($gameState->getGame()->getSpawnPoint(), $gameState->getPlayerRoom()->getName());
    $this->assertEquals($gameState->getGame()->getRoom($gameState->getGame()->getSpawnPoint())->getName(), $gameState->getPlayerRoom()->getName());
    $this->assertContains("Game restarted.", $gameState->getConsoleHistory());
  }
}
