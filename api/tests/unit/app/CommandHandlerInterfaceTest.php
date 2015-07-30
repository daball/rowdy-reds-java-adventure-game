<?php

require_once __DIR__.'/../../../vendor/phpunit/phpunit/src/Framework/TestCase.php';
require_once __DIR__.'/../../../app/CommandHandlerInterface.php';

///Unit tests CommandHandlerInterface class
class CommandHandlerInterfaceTest extends PHPUnit_Framework_TestCase
{
  public function testCommandHandlerInterface()
  {
    $commandLine = "any arbitrary input";

    $commandHandler = new CommandHandlerInterface();

    $this->assertFalse($commandHandler->validateCommand(null, $commandLine));
    $this->assertEquals("", $commandHandler->executeCommand(null, $commandLine));
  }
}
