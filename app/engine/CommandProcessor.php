<?php

namespace engine;

use \commands\AutoSuggest;

class CommandProcessor
{
  protected static $commandHandlers;
  protected $commandInput;
  protected $commandOutput;

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
        $autoSuggest = new AutoSuggest();
        if ($autoSuggest->validateCommand($commandLine)) {
          $commandOutput = $autoSuggest->executeCommand($commandLine);
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

  public function __construct()
  {
    $commandLine = "";
    if (isset($_POST['commandLine']))
    	$commandLine = $_POST['commandLine'];
    else if (isset($_SERVER['REQUEST_METHOD'])
          && $_SERVER['REQUEST_METHOD'] == 'POST'
          && isset($_SERVER['HTTP_ACCEPT'])
          && strstr($_SERVER['HTTP_ACCEPT'], 'application/json') !== FALSE) {
    	$data = json_decode(file_get_contents('php://input'), true);
    	if (isset($data['commandLine']))
    		$commandLine = $data['commandLine'];
    }
    if ($commandLine)
    {
      $this->commandInput = trim($commandLine);
      $this->commandOutput = $this->dispatchCommand($this->commandInput);
      GameState::getInstance()->addCommandToHistory($this->commandInput, $this->commandOutput);
    }
    else
    {
      $this->commandInput = "";
      $this->commandOutput = "";
    }
  }
}

CommandProcessor::init();
