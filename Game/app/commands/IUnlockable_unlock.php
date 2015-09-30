<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

class IUnlockable_unlockCommandHandler extends BaseCommandHandler
{

  use TUsesItems;

  public function validateCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.unlock\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches))
    {
      return true;
    }
    return false;
  }

  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.unlock\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches))
    {
      $item = $this->isRoomItem($matches[1]);
      if ($item !== FALSE && is_a($item, "\playable\IUnlockable")) {
        if (substr($matches[2], -8) === "leftHand") {
          if (is_a($gameState->getPlayer()->leftHand, "\playable\Key")) {
            return $item->unlock($gameState->getPlayer()->leftHand);
          }
        }
        else if (substr($matches[2], -9) === "rightHand") {
          if (is_a($gameState->getPlayer()->rightHand, "\playable\Key")) {
            return $item->unlock($gameState->getPlayer()->rightHand);
          }
        }
      }
    }
    return "You can't do that.";
  }

}

CommandProcessor::addCommandHandler(new IUnlockable_unlockCommandHandler());
