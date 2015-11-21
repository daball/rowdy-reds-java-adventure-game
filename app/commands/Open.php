<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class Open extends BaseCommandHandler
{

  public function validateCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    return preg_match('/([\w$_]+[\w\d$_]*)\.open\(\);/', $commandLine, $matches);
  }

  public function executeCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    if (preg_match('/([\w$_]+[\w\d$_]*)\.open\(\);/', $commandLine, $matches))
    {
      $target = $matches[1];
      if (($search = $gameState->getPlayerRoom()->getComponent('Container')->findNestedItemByName($target))) {
        $target = $search;
      }
      else
        return "I don't know what " . insertAOrAn($target) . " is.";
      if (!$target->hasComponent('Openable'))
        return "You cannot open " . $target->getName() . ".";
      $gameState->incrementMoves();
      return $target->getComponent('Openable')->open();
    }
    return "You can not do that.";
  }

}

CommandProcessor::addCommandHandler(new Open());
