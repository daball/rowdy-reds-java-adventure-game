<?php

namespace engine\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/engine/GameEngine.php';

use \engine\GameEngine;

class GameEngineTest extends \PHPUnit_Framework_TestCase
{
  public function testGameEngine()
  {
    $validGames = GameEngine::getValidGames();
    $this->assertNotEmpty($validGames);
    foreach ($validGames as $gameName) {
      $this->assertTrue(GameEngine::isValidGame($gameName));
      $game = GameEngine::loadGame($gameName);
      $this->assertFalse(!!GameEngine::loadGame("notavalidgamename"));
      $this->assertEquals($gameName, $game->getName());

      $gameEngine = new GameEngine($gameName);
      $this->assertEquals($gameName, $gameEngine->getGameState()->getGame()->getName());
    }
  }
}
