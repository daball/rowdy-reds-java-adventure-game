<?php

namespace engine\tests;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/engine/GameEngine.php';
require_once __DIR__.'/../../../../app/engine/CommandProcessor.php';
require_once __DIR__.'/../../../../app/commands/index.php';
require_once __DIR__.'/../../../../app/engine/GameState.php';

use \engine\GameEngine;
use \engine\CommandProcessor;
use \engine\GameState;

class CommandProcessorTest extends \PHPUnit_Framework_TestCase
{
  public function testCommandProcessor()
  {
    $validCommandLines = array(
      "help", "HELP", "heLP", "Help", "HElp", "?",
      "north", "n", "NORTH", "noRTH", "North", "N", "moveNorth();",
      "south", "s", "SOUTH", "soUTH", "South", "S", "moveSouth();",
      "east", "e", "EAST", "eaST", "East", "E", "moveEast();",
      "west", "w", "WEST", "weST", "West", "W", "moveWest();",
      "reset", "restart", "RESET", "RESTART", "Reset", "Restart", "rEsEt", "rEstARt",
      "exit", "System.exit(0);",
    );
    $invalidCommandLines = array(
      "aoeu", "asdf", "123",
      "movenorth();", "moveNorth()",
      "movesouth();", "moveSouth()",
      "moveeast();", "moveEast()",
      "movewest();", "moveWest()",
    );

    foreach ($validCommandLines as $validCommandLine)
    {
      $_POST['commandLine'] = $validCommandLine;
      $gameState = GameState::init('Iteration 0');
      $commandProcessor = new CommandProcessor($gameState);
      $this->assertNotEquals("I do not understand.", $commandProcessor->getCommandOutput());
    }

    foreach ($invalidCommandLines as $invalidCommandLine)
    {
      $_POST['commandLine'] = $invalidCommandLine;
      $gameState = GameState::init('Iteration 0');
      $commandProcessor = new CommandProcessor($gameState);
      $this->assertEquals("I do not understand.", $commandProcessor->getCommandOutput());
    }
  }
}
