<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/CommandProcessor.php';

///Unit tests CommandProcessor class
class CommandProcessorTest extends PHPUnit_Framework_TestCase
{
  public function testHelpCommand()
  {
    $validCommandLines = array(
      "help", "HELP", "heLP", "Help", "HElp",
      "north", "n", "NORTH", "noRTH", "North", "N",
      "south", "s", "SOUTH", "soUTH", "South", "S",
      "east", "e", "EAST", "eaST", "East", "E",
      "west", "w", "WEST", "weST", "West", "W",
      );

    $commandProcessor = new CommandProcessor();

    foreach ($validCommandLines as $validCommandLine)
    {
      $gameState = new GameState();
      $commandProcessor->commandInput = $validCommandLine;
      $commandProcessor->dispatchCommand($gameState, $validCommandLine);
      $this->assertTrue($commandHandler->validateCommand(null, $validCommandLine));
      $this->assertNotEquals("", $commandHandler->executeCommand(null, $validCommandLine));
    }
  }
}
