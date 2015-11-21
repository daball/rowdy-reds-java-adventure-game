<?php

namespace engine;

use \commands\AutoSuggest;

class CommandProcessor
{
  protected static $commandHandlers;
  protected $commandInput;
  protected $commandOutput;
  protected $tabletInput;

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

  public function dispatchCommand($commandLine, $tabletCode)
  {
    $this->commandInput = $commandLine;
    $this->tabletInput = $tabletCode;
    $commandLine = trim($commandLine);
    $commandOutput = "";
    if ($commandLine !== "")
    {
      foreach (self::$commandHandlers as $commandHandler)
      {
        if ($commandHandler->validateCommand($commandLine, $tabletCode))
        {
          $commandOutput = $commandHandler->executeCommand($commandLine, $tabletCode);
          break; //stop foreach
        }
      }
      if ($commandOutput === "")
      {
        $autoSuggest = new AutoSuggest();
        if ($autoSuggest->validateCommand($commandLine, $tabletCode)) {
          $commandOutput = $autoSuggest->executeCommand($commandLine, $tabletCode);
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

  public function getCommandInput() {
    return $this->commandInput;
  }

  public function getCommandOutput() {
    return $this->commandOutput;
  }

  public function __construct($commandLine, $tabletCode)
  {
    $this->commandInput = trim($commandLine);
    $this->tabletInput = trim($tabletCode);
    if (!$this->commandInput)
    {
      $this->commandInput = "";
      $this->tabletCode = "";
      $this->commandOutput = "";
    }
    else {
      $this->commandOutput = $this->dispatchCommand($this->commandInput, $this->tabletInput);
    }
    GameState::getInstance()->addCommandToHistory($this->commandInput, $this->commandOutput, $this->tabletInput);
  }
}

CommandProcessor::init();
