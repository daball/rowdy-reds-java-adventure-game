<?php

namespace game\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/engine/GameEngine.php';
require_once __DIR__.'/../../../../app/game/GameBuilder.php';
require_once __DIR__.'/../../../../app/games/Iteration0.php';

use \engine\GameEngine;
use \game\MapBuilder;
use \games\Iteration0;

class Iteration0Test extends \PHPUnit_Framework_TestCase
{
  public function testIteration0()
  {
    //build sample map
    $game = GameEngine::loadGame('Iteration0');

    //check for known rooms
    $this->assertEquals('entrance', $game->getRoom('entrance')->getName());
    $this->assertNotNull($game->getRoom('entrance')->getImageUrl());
    $this->assertContains('castle door', $game->getRoom('entrance')->getComponent('Inspector')->inspect());

    $this->assertEquals('hall', $game->getRoom('hall')->getName());
    $this->assertNotNull($game->getRoom('hall')->getImageUrl());
    $this->assertContains('lavish', $game->getRoom('hall')->getComponent('Inspector')->inspect());
    $this->assertContains('hallway', $game->getRoom('hall')->getComponent('Inspector')->inspect());

    $this->assertEquals('kitchen', $game->getRoom('kitchen')->getName());
    $this->assertNotNull($game->getRoom('kitchen')->getImageUrl());
    $this->assertContains('kitchen', $game->getRoom('kitchen')->getComponent('Inspector')->inspect());

    //test forward direction
    $this->assertEquals('hall', $game->getRoom('entrance')->getDirection('n')->getNextRoomName());
    $this->assertEquals('kitchen', $game->getRoom('hall')->getDirection('w')->getNextRoomName());

    //test reverse direction
    $this->assertEquals('entrance', $game->getRoom('hall')->getDirection('s')->getNextRoomName());
    $this->assertEquals('hall', $game->getRoom('kitchen')->getDirection('e')->getNextRoomName());

    //test spawn point
    $this->assertEquals('entrance', $game->getSpawnPoint());
  }
}
