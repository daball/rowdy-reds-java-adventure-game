<?php

namespace commands;
use game\CommandProcessor;

require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class ICloseable_closeCommandHandler extends BaseCommandHandler
{

  public function validateCommand($gameState, $commandLine)
  {
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.close\(\)/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      if (array_key_exists($itemName, $gameState->getPlayerRoom()->items)) {
        $item = $gameState->getPlayerRoom()->items[$itemName];
        return is_a($item, "\playable\ICloseable");
      }
      else {
        return true;
      }
    }
    return false;
  }

  public function executeCommand($gameState, $commandLine)
  {
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.close\(\)/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      if (array_key_exists($itemName, $gameState->getPlayerRoom()->items)) {
        $item = $gameState->getPlayerRoom()->items[$itemName];
        if (is_a($item, "\playable\ICloseable"))
        {
          return $item->close();
        }
        else
        {
          return "You can't do that. $itemName is not closeable.";
        }
      }
      else
      {
        return "You can't do that. I don't see a $itemName here.";
      }
    }
  }

}

CommandProcessor::addCommandHandler(new ICloseable_closeCommandHandler());
