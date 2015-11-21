<?php

namespace commands\tests;
use \commands\Help;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/commands/Help.php';

///Unit tests HelpCommandHandler class
class HelpTest extends \PHPUnit_Framework_TestCase
{
  public function testHelpCommand()
  {
    $validCommandLines = array("help", "HELP", "heLP", "Help", "HElp", "?");

    $commandHandler = new Help();

    foreach ($validCommandLines as $validCommandLine)
    {
      $this->assertTrue($commandHandler->validateCommand($validCommandLine, ""));
      $this->assertContains("HELP", strtoupper($commandHandler->executeCommand($validCommandLine, "")));
    }
  }
}
