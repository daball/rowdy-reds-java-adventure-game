<?php

require_once 'CommandHandlerInterface.php';

require_once 'NavigateCommandHandler.php';

class CommandProcessor
{
  public $commandHandlers;
  public $commandInput;
  public $commandOutput;

  public function dispatchCommandLine($gameState, $commandLine)
  {
    $commandLine = trim($commandLine);
    $commandOutput = "";
    if ($commandLine !== "")
    {
      foreach ($this->commandHandlers as $commandHandler)
      {
        if ($commandHandler->validateCommand($commandLine))
        {
          $commandOutput = $commandHandler->executeCommand($gameState, $commandLine);
          break; //stop foreach
        }
      }
      $commandOutput = "I do not understand.";
    }
    return trim($commandOutput);
  }

  public function __construct($gameState)
  {
    //TO IMPLEMENTERS: Please register all command handlers here.
    $this->commandHandlers = array(
      (new NavigateCommandHandler()),
      (new HelpCommandHandler()),
    );
    if (isset($_POST['commandLine']))
    {
      $this->commandInput = $_POST['commandLine'];
      $this->commandOutput = $this->dispatchCommandLine($gameState, $commandInput);
      $this->gameState->addCommandToHistory($this->commandInput, $this->commandOutput);
    }
    else
    {
      $this->commandInput = "";
      $this->commandOutput = "";
    }
  }
}
