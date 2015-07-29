<?php

require_once 'CommandHandlerInterface.php';

//TO IMPLEMENTERS: install each of the command handlers
require_once 'NavigateCommandHandler.php';
require_once 'ResetCommandHandler.php';
require_once 'HelpCommandHandler.php';
require_once 'ExitCommandHandler.php';

class CommandProcessor
{
  public $commandHandlers;
  public $commandInput;
  public $commandOutput;

  public function dispatchCommand($gameState, $commandLine)
  {
    $this->commandInput = $commandLine;
    $commandLine = trim($commandLine);
    $commandOutput = "";
    if ($commandLine !== "")
    {
      foreach ($this->commandHandlers as $commandHandler)
      {
        if ($commandHandler->validateCommand($gameState, $commandLine))
        {
          $commandOutput = $commandHandler->executeCommand($gameState, $commandLine);
          break; //stop foreach
        }
      }
      if ($commandOutput === "")
      {
        $commandOutput = "I do not understand.";
      }
    }
    $this->commandOutput = trim($commandOutput);
    return trim($this->commandOutput);
  }

  public function __construct($gameState)
  {
    //TO IMPLEMENTERS: Please register all command handlers here.
    $this->commandHandlers = array(
      (new NavigateCommandHandler()),
      (new HelpCommandHandler()),
      (new ResetCommandHandler()),
      (new ExitCommandHandler()),
    );
    if (isset($_POST['commandLine']))
    {
      $this->commandInput = $_POST['commandLine'];
      $this->commandOutput = $this->dispatchCommand($gameState, $this->commandInput);
      $gameState->addCommandToHistory($this->commandInput, $this->commandOutput);
    }
    else
    {
      $this->commandInput = "";
      $this->commandOutput = "";
    }
  }
}
