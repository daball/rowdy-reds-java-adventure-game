<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../util/BasicEnglish.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

class Lock extends BaseCommandHandler
{

  use TUsesItems;

  public function validateCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    return preg_match('/([\w$_]+[\w\d$_]*)\.lock\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches);
  }

  public function executeCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.lock\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches))
    {
      $target = $matches[1];
      $keyAt = $matches[2];
      if (($search = $gameState->getPlayerRoom()->getComponent('Container')->findNestedItemByName($target))) {
        $target = $search;
      }
      else
        return "I don't know what " . insertAOrAn($target) . " is.";
      if ($keyAt == "leftHand" || $keyAt == "me.leftHand")
          $keyAt = $gameState->getPlayer()->getLeftHand();
      else if ($keyAt == "rightHand" || $keyAt == "me.rightHand")
          $keyAt = $gameState->getPlayer()->getRightHand();
      else
        return "Cannot lock " . insertAOrAn($target->getName()) . " with $keyAt.";
      if (!$target->hasComponent('Lockable'))
        return "You cannot lock " . $target->getName() . ".";
      if (!$keyAt->getComponent('Container')->hasItemAt(0))
        return "Your " . $keyAt->getName() . " is empty.";
      $candidateKey = $keyAt->getComponent('Container')->getItemAt(0);
      if (!is_a($candidateKey, '\playable\Key'))
        return "You cannot lock " . $target->getName() . " with " . $candidateKey->getName() . ".";
      $key = $candidateKey;
      $gameState->incrementMoves();
      return $target->getComponent('Lockable')->lock($key);
    }
    return "You can not do that.";
  }

}

CommandProcessor::addCommandHandler(new Lock());
