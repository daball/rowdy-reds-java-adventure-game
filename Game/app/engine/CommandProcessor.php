<?php

namespace engine;

class CommandProcessor
{
  public static $commandHandlers;
  public $commandInput;
  public $commandOutput;

  public static function init()
  {
    if (!self::$commandHandlers)
      self::$commandHandlers = array();
  }

  public static function addCommandHandler($commandHandler)
  {
    if (!self::$commandHandlers)
      self::init();
    array_push(self::$commandHandlers, $commandHandler);
  }

  public function dispatchCommand($commandLine)
  {
    $this->commandInput = $commandLine;
    $commandLine = trim($commandLine);
    $commandOutput = "";
    if ($commandLine !== "")
    {
      foreach (self::$commandHandlers as $commandHandler)
      {
        if ($commandHandler->validateCommand($commandLine))
        {
          $commandOutput = $commandHandler->executeCommand($commandLine);
          break; //stop foreach
        }
      }
      if ($commandOutput === "")
      {
        $commandOutput = "I do not understand.";
      }
    }
    $this->commandOutput = trim($commandOutput);
    return $this->commandOutput;
  }

  public function __construct()
  {
    if (isset($_POST['commandLine']))
    {
      $this->commandInput = $_POST['commandLine'];
      $this->commandOutput = $this->dispatchCommand($this->commandInput);
      GameState::getGameState()->addCommandToHistory($this->commandInput, $this->commandOutput);
    }
    else
    {
      $this->commandInput = "";
      $this->commandOutput = "";
    }
  }
}

CommandProcessor::init();
