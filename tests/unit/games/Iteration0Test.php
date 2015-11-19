<?php

namespace game\tests;

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/engine/GameEngine.php';
require_once __DIR__.'/../../../app/game/GameBuilder.php';
require_once __DIR__.'/../../../games/Iteration 0.php';

use \engine\GameEngine;
use \game\MapBuilder;

class Iteration0Test extends \PHPUnit_Framework_TestCase
{
  public function testIteration0()
  {
    //build sample map
    $game = GameEngine::loadGame('Iteration 0');

    //check for known rooms
    $this->assertEquals('Castle Entrance', $game->getRoom('Castle Entrance')->getName());
    $this->assertNotNull($game->getRoom('Castle Entrance')->getImageUrl());
    $this->assertContains('castle door', $game->getRoom('Castle Entrance')->getComponent('Inspector')->inspect());

    $this->assertEquals('Main Hall', $game->getRoom('Main Hall')->getName());
    $this->assertNotNull($game->getRoom('Main Hall')->getImageUrl());
    $this->assertContains('lavish', $game->getRoom('Main Hall')->getComponent('Inspector')->inspect());
    $this->assertContains('hallway', $game->getRoom('Main Hall')->getComponent('Inspector')->inspect());

    $this->assertEquals('Kitchen', $game->getRoom('Kitchen')->getName());
    $this->assertNotNull($game->getRoom('Kitchen')->getImageUrl());
    $this->assertContains('kitchen', $game->getRoom('Kitchen')->getComponent('Inspector')->inspect());

    //test forward direction
    $this->assertEquals('Main Hall', $game->getRoom('Castle Entrance')->getDirection('n')->getNextRoomName());
    $this->assertEquals('Kitchen', $game->getRoom('Main Hall')->getDirection('w')->getNextRoomName());

    //test reverse direction
    $this->assertEquals('Castle Entrance', $game->getRoom('Main Hall')->getDirection('s')->getNextRoomName());
    $this->assertEquals('Main Hall', $game->getRoom('Kitchen')->getDirection('e')->getNextRoomName());

    //test spawn point
    $this->assertEquals('Castle Entrance', $game->getSpawnPoint());
  }
}
