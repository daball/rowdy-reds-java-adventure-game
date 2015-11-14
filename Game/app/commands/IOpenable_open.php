<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class IOpenable_openCommandHandler extends BaseCommandHandler
{

  public function validateCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.open\(\);/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      if ($gameState->getPlayerRoom()->keyExists($itemName)) {
        $item = $gameState->getPlayerRoom()->getItem($itemName);
        return is_a($item, "\playable\IOpenable");
      }
      else {
        return true;
      }
    }
    return false;
  }

  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.open\(\);/', $commandLine, $matches))
    {
      $itemName = $matches[1];
      if ($gameState->getPlayerRoom()->keyExists($itemName)) {
        $item = $gameState->getPlayerRoom()->getItem($itemName);
        if (is_a($item, "\playable\IOpenable"))
        {
          return $item->open();
        }
        else
        {
          return "You can't do that. $itemName is not openable.";
        }
      }
      else
      {
        return "You can't do that. I don't see a $itemName here.";
      }
    }
  }

}

CommandProcessor::addCommandHandler(new IOpenable_openCommandHandler());
