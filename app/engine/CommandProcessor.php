<?php

namespace engine;

require_once 'Router.php';
require_once __DIR__.'/../commands/AutoSuggest.php';
require_once __DIR__.'/../util/PubSubMessageQueue.php';

use \commands\AutoSuggest;
use \util\PubSubMessageQueue;

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
    $commandOutput = Router::dispatch($commandLine, $tabletCode);
    // if ($commandLine !== "")
    // {
    //   foreach (self::$commandHandlers as $commandHandler)
    //   {
    //     if ($commandHandler->validateCommand($commandLine, $tabletCode))
    //     {
    //       $commandOutput = $commandHandler->executeCommand($commandLine, $tabletCode);
    //       break; //stop foreach
    //     }
    //   }
      if ($commandOutput === "")
      {
        $autoSuggest = new AutoSuggest();
        if ($autoSuggest->validateCommand($commandLine, $tabletCode)) {
          $commandOutput = $autoSuggest->executeCommand($commandLine, $tabletCode);
        }
      }
      if ($commandOutput === "")
        $commandOutput = "I do not understand.";
      if ($commandOutput === null)
        $commandOutput = "";
    // }
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
    $this->commandOutput = "";
    if ($this->commandInput)
      $this->commandOutput = $this->dispatchCommand($this->commandInput, $this->tabletInput);
    $prependOutput = "";
    $appendOutput = "";

    //since the command processor is the last thing to run, there should be no messages
    //arriving later than this, therefore we can assume that this callback will run immediately
    //for each message hanging around in the pub/sub queue
    PubSubMessageQueue::subscribe("System.out", function ($sender, $queue, $message) use (&$prependOutput, &$appendOutput) {
      if (is_array($message)) {
        if (array_key_exists('prepend', $message))
          $prependOutput .= $message['prepend'] . '  ';
        if (array_key_exists('append', $message))
          $appendOutput .= '  ' . $message['append'];
      }
      else
        $appendOutput .= '  ' . $message;
    });
    //multiplex command outputs
    $this->commandOutput = $prependOutput . $this->commandOutput . $appendOutput;
    GameState::getInstance()->addCommandToHistory($this->commandInput, $this->commandOutput, $this->tabletInput);
  }
}

CommandProcessor::init();
