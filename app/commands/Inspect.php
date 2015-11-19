<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../game/Direction.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

use engine\CommandProcessor;
use engine\GameState;
use game\Direction;
use game\DirectionException;

function is_vowel($letter) {
  $letter = strtolower($letter);
  return $letter === 'a'
      || $letter === 'e'
      || $letter === 'i'
      || $letter === 'o'
      || $letter === 'u';
}

function insertAOrAn($beforeWord) {
  if (is_vowel(substr($beforeWord, 0, 1)))
    return "an $beforeWord";
  return "a $beforeWord";
}

class Inspect extends BaseCommandHandler
{
  use TUsesItems;

  private function getTargetName($commandLine)
  {
    if (stripos($commandLine, 'inspect') === 0)
      return trim(substr($commandLine, 8));
    else if (stripos($commandLine, 'look') === 0)
      return trim(substr($commandLine, 5));
    else
      return "";
  }

  ///Validates the incoming command line for reset commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  stripos($commandLine, 'inspect') === 0 ||
            stripos($commandLine, 'look') === 0;
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $gameState = GameState::getInstance();
    $inspectWhat = $this->getTargetName($commandLine);
    // echo $inspectWhat;
    if ($inspectWhat === "" || strtolower($inspectWhat) == 'room')
      //no parameters, inspect the room
      return $gameState->inspectRoom();
    else if (Direction::cardinalDirection($inspectWhat))
      //direction provided, inspect direction from inside room
      return $gameState->getPlayerRoom()->getDirection($inspectWhat)->getComponent('Inspector')->inspect();
    else if ($inspectWhat == 'leftHand'
          || $inspectWhat == 'me.leftHand')
      //left hand provided, inspect contents of left hand
      return $gameState->getPlayer()->getLeftHand()->getComponent('Inspector')->inspect();
    else if ($inspectWhat == 'rightHand'
          || $inspectWhat == 'me.rightHand')
      //right hand provided, inspect contents of right hand
      return $gameState->getPlayer()->getRightHand()->getComponent('Inspector')->inspect();
    else if (($inspectWhat == 'backpack'
          || $inspectWhat == 'me.backpack')
          && $gameState->getPlayer()->getBackpack())
      //backpack provided, inspect contents of backpack
      return $gameState->getPlayer()->getBackpack()->getComponent('Inspector')->inspect();
    else if (($item = $gameState->getPlayerRoom()->getComponent('Container')->findNestedItemByName($inspectWhat)) != null)
      //room item (or item within any container hierarchy in room) provided, inspect item
      return $item->getComponent('Inspector')->inspect();
    // else if ($item = $gameState->getPlayer()->getBackpack()->getComponent('Container')->findNestedItemByName($inspectWhat)) != null)
      //backpack item provided, inspect contents of back pack
      // return $item->getComponent('Inspector')->inspect();
    // else {
    //   else if (($item = $isItemInContainerInRoom($itemInQuestion)) !== FALSE) {
    //     if (is_a($item, '\playable\IInspectable'))
    //       return $item->inspect();
    //     else
    //       return "The item in the room is not inspectable.";
    //   }
    return "I don't know what " . insertAOrAn($inspectWhat) . " is.";
  }
}

CommandProcessor::addCommandHandler(new Inspect());
