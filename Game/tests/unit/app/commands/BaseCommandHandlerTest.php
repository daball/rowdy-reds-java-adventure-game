<?php

namespace commands\tests;
use \commands\BaseCommandHandler;

require_once __DIR__.'/../../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../../app/commands/BaseCommandHandler.php';

class AParticularCommandHandler extends BaseCommandHandler {

}

///Unit tests CommandHandlerInterface class
class BaseCommandHandlerTest extends PHPUnit_Framework_TestCase
{
  public function testCommandHandlerInterface()
  {
    $commandLine = "any arbitrary input";

    $commandHandler = new AParticularCommandHandler();

    $this->assertFalse($commandHandler->validateCommand(null, $commandLine));
    $this->assertEquals("", $commandHandler->executeCommand(null, $commandLine));
  }
}
