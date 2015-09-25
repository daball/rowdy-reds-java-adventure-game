<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/commands/BaseCommandHandler.php';

///Unit tests CommandHandlerInterface class
class BaseCommandHandler extends PHPUnit_Framework_TestCase
{
  public function testCommandHandlerInterface()
  {
    $commandLine = "any arbitrary input";

    $commandHandler = new BaseCommandHandler();

    $this->assertFalse($commandHandler->validateCommand(null, $commandLine));
    $this->assertEquals("", $commandHandler->executeCommand(null, $commandLine));
  }
}
