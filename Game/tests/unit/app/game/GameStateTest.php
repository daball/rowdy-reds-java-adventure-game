<?php

namespace game\tests;
use \game\GameState;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/GameState.php';

///Unit tests GameState class
class GameStateTest extends \PHPUnit_Framework_TestCase
{
  public function testGameState()
  {
    $gameState = new GameState();

    $this->assertEquals($gameState->map->getSpawnPoint(), $gameState->getPlayer()->location);
    $this->assertEquals($gameState->map->getRoom($gameState->map->getSpawnPoint())->name, $gameState->getPlayer()->location);
    $this->assertContains("Game started.", $gameState->consoleHistory);
    $this->assertContains($gameState->getPlayerRoom()->inspect(), $gameState->consoleHistory);

    //test inspect room
    $this->assertEquals($gameState->getPlayerRoom()->inspect(), $gameState->map->getRoom($gameState->getPlayer()->location)->description);
    $this->assertEquals($gameState->getPlayer()->location, $gameState->getPlayerRoom()->name);
    $this->assertEquals($gameState->getPlayerRoom()->inspect(), $gameState->getPlayerRoom()->description);

    //move the character's location, one room to the north
    $initialRoom = $gameState->getPlayer()->location;
    $gameState->navigate('north');
    $this->assertNotEquals($initialRoom, $gameState->getPlayer()->location);

    //reset the game state
    $gameState->resetGameState();
    $this->assertEquals($gameState->map->getSpawnPoint(), $gameState->getPlayer()->location);
    $this->assertEquals($gameState->map->getRoom($gameState->map->getSpawnPoint())->name, $gameState->getPlayer()->location);
    $this->assertContains("Game restarted.", $gameState->consoleHistory);
  }
}
