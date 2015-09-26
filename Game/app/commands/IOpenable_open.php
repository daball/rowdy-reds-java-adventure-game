<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class IOpenable_openCommandHandler extends BaseCommandHandler
{

  public function validateCommand($gameState, $commandLine)
  {
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.open\(\)/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      $item = $gameState->getPlayerRoom()->items[$itemName];
      return is_a($item, "\playable\IOpenable");
    }
    return false;
  }

  public function executeCommand($gameState, $commandLine)
  {
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.open\(\)/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      $item = $gameState->getPlayerRoom()->items[$itemName];
      return $item->open();
    }
  }

}

CommandProcessor::addCommandHandler(new IOpenable_openCommandHandler());
