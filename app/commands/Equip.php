<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../util/BasicEnglish.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

use engine\CommandProcessor;
use engine\GameState;

class Equip extends BaseCommandHandler
{
  use TUsesItems;

  public function validateCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    if (preg_match('/(?:me.){0,1}equip\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches))
    {
      return true;
    }
    return false;
  }

  public function executeCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $player = $gameState->getPlayer();
    $matches = array();
    if (preg_match('/(?:me.){0,1}equip\(([\w$_]*[\w\d$_\.]*)\);/', $commandLine, $matches))
    {
      $item = $matches[1];

      //evalute item
      if ($item == 'leftHand' || $item == 'me.leftHand') {
        if ($gameState->getPlayer()->getLeftHand()->getComponent('Container')->hasItemAt(0))
          $item = $gameState->getPlayer()->getLeftHand()->getComponent('Container')->getItemAt(0);
        else
          return "You cannot do that, your left hand is empty.";
      }
      else if ($item == 'rightHand' || $item == 'me.rightHand') {
        if ($gameState->getPlayer()->getRightHand()->getComponent('Container')->hasItemAt(0))
          $item = $gameState->getPlayer()->getRightHand()->getComponent('Container')->getItemAt(0);
        else
          return "You cannot do that, your right hand is empty.";
      }
      // else if ($target == 'backpack' || $target == 'me.backpack')
      //   $target = $gameState->getPlayer()->getBackpack()
      else if ($search = $gameState->getPlayerRoom()->getComponent('Container')->findNestedItemByName($item))
        $item = $search;
      else
        return "I don't know what " . insertAOrAn($item) . " is.";

      //pre-assignment check
      if (!$item->hasComponent('Equippable'))
        return "$item is not an equippable item.";

      //perform equip
      return $player->equipItem($item);
    }
    return "I don't know what to do.";
  }

}

CommandProcessor::addCommandHandler(new Equip());
