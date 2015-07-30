<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/GameState.php';

///Unit tests GameState class
class GameStateTest extends PHPUnit_Framework_TestCase
{
  public function testGameState()
  {
    $gameState = new GameState();

    $this->assertEquals($gameState->map->getSpawnPoint(), $gameState->avatarLocation);
    $this->assertEquals($gameState->map->getRoom($gameState->map->getSpawnPoint())->name, $gameState->avatarLocation);
    $this->assertContains("Game started.", $gameState->consoleHistory);
    $this->assertContains($gameState->inspectRoom(), $gameState->consoleHistory);

    //test inspect room
    $this->assertEquals($gameState->inspectRoom(), $gameState->map->getRoom($gameState->avatarLocation)->description);
    $this->assertEquals($gameState->avatarLocation, $gameState->getAvatarRoom()->name);
    $this->assertEquals($gameState->inspectRoom(), $gameState->getAvatarRoom()->description);

    //move the character's location, one room to the north
    $initialRoom = $gameState->avatarLocation;
    $gameState->navigate('north');
    $this->assertNotEquals($initialRoom, $gameState->avatarLocation);

    //reset the game state
    $gameState->resetGameState();
    $this->assertEquals($gameState->map->getSpawnPoint(), $gameState->avatarLocation);
    $this->assertEquals($gameState->map->getRoom($gameState->map->getSpawnPoint())->name, $gameState->avatarLocation);
    $this->assertContains("Game restarted.", $gameState->consoleHistory);
  }
}
