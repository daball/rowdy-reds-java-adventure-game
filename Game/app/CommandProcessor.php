<?php

require_once 'CommandHandlerInterface.php';

require_once 'NavigateCommandHandler.php';

class CommandProcessor
{
  public $gameState;
  public $commandHandlers;
  public $commandInput;
  public $commandOutput;

  public function dispatchCommandLine($gameState, $commandLine)
  {
    foreach ($this->commandHandlers as $commandHandler)
    {
      if ($commandHandler->validateCommand($commandLine))
      {
        return $commandHandler->executeCommand($commandLine);
      }
    }
  }

  public function __construct($gameEngine)
  {
    $this->gameEngine = $gameEngine;
    $this->commandHandlers = array(
      (new NavigateCommandHandler())
    );
    $this->commandInput = $_POST['commandLine'];
    $this->commandOutput = $this->dispatchCommandLine($commandInput);
  }
}

new CommandProcessor(null);
