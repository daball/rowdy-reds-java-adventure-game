<?php

namespace game;

class CommandProcessor
{
  public static $commandHandlers;
  public $commandInput;
  public $commandOutput;

  public static function init()
  {
    self::$commandHandlers = array();
  }

  public static function addCommandHandler($commandHandler)
  {
    if (!self::$commandHandlers)
      self::init();
    array_push(self::$commandHandlers, $commandHandler);
  }

  public function dispatchCommand($gameState, $commandLine)
  {
    $this->commandInput = $commandLine;
    $commandLine = trim($commandLine);
    $commandOutput = "";
    if ($commandLine !== "")
    {
      foreach (self::$commandHandlers as $commandHandler)
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

CommandProcessor::init();
