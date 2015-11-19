<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../util/BasicEnglish.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

use engine\CommandProcessor;
use engine\GameState;

class Assign extends BaseCommandHandler
{
  use TUsesItems;

  public function validateCommand($commandLine)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    if (preg_match('/\s*([\w\d$_.]+)\s*=\s*([\w\d$_.]+)\s*;/', $commandLine, $matches))
    {
      return true;
    }
    return false;
  }

  public function executeCommand($commandLine)
  {
    $gameState = GameState::getInstance();
    $matches = array();
    if (preg_match('/\s*([\w\d$_.]+)\s*=\s*([\w\d$_.]+)\s*;/', $commandLine, $matches))
    {
      $target = $matches[1]; //left side of equals, assignment target
      $index = -1; //where in the target to assign
      $item = $matches[2]; //right side of equals, assignment item

      //evaluate target
      if ($target == 'leftHand' || $target == 'me.leftHand')
        $target = $gameState->getPlayer()->getLeftHand();
      else if ($target == 'rightHand' || $target == 'me.rightHand')
        $target = $gameState->getPlayer()->getRightHand();
      // else if ($target == 'backpack' || $target == 'me.backpack')
      //   $target = $gameState->getPlayer()->getBackpack()
      else if ($search = $gameState->getPlayerRoom()->getComponent('Container')->findNestedItemByName($inspectWhat))
        $target = $search;
      else
        return "I don't know what " . insertAOrAn($target) . " is.";

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
      if (!$item->hasComponent('Assignable'))
        return "$item is not an assignable item.";
      if (!$target->hasComponent('Container'))
        return "$target is not a valid place to assign the $item.";
      //perform assignment
      return $item->getComponent('Assignable')->assignTo($target, $index);
    }
    return "I don't know what to do.";
  }

}

CommandProcessor::addCommandHandler(new Assign());
