<?php

namespace commands\tests;
use \commands\HelpCommandHandler;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/commands/Help.php';

///Unit tests HelpCommandHandler class
class HelpCommandHandlerTest extends \PHPUnit_Framework_TestCase
{
  public function testHelpCommand()
  {
    $validCommandLines = array("help", "HELP", "heLP", "Help", "HElp");

    $commandHandler = new HelpCommandHandler();

    foreach ($validCommandLines as $validCommandLine)
    {
      $this->assertTrue($commandHandler->validateCommand(null, $validCommandLine));
      $this->assertNotEquals("", $commandHandler->executeCommand(null, $validCommandLine));
    }
  }
}
