<?php

namespace game\tests;
use \game\CommandProcessor;
use \game\GameState;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/game/CommandProcessor.php';
require_once __DIR__.'/../../../../app/commands/index.php';
require_once __DIR__.'/../../../../app/game/GameState.php';

///Unit tests CommandProcessor class
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
      $gameState = new GameState();
      $commandProcessor = new CommandProcessor($gameState);
      $this->assertNotEquals("I do not understand.", $commandProcessor->commandOutput);
    }

    foreach ($invalidCommandLines as $invalidCommandLine)
    {
      $_POST['commandLine'] = $invalidCommandLine;
      $gameState = new GameState();
      $commandProcessor = new CommandProcessor($gameState);
      $this->assertEquals("I do not understand.", $commandProcessor->commandOutput);
    }
  }
}
