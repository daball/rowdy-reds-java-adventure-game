<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class IAssignable_assignCommandHandler extends BaseCommandHandler
{

  // /**
  //  * Is this a player defined object in variable bag?
  //  **/
  // private function isLocal($itemInQuestion) {
  // }

  /**
   * Is this an object assigned to the player?
   **/
  private function isPlayerItem($itemInQuestion) {
    return ($itemInQuestion == "me.leftHand" || $itemInQuestion == "me.rightHand")
        || ($itemInQuestion == "leftHand" || $itemInQuestion == "rightHand");
  }

  /**
   * Is this an object contained in the room?
   **/
  private function isRoomItem($itemInQuestion) {
    $room = GameState::getGameState()->getPlayerRoom();
    foreach ($room->items as $itemName => $item)
    {
      if ($itemName == $itemInQuestion)
        return $item;
    }
    return false;
  }

  /**
   * Is this an object contained in a container in the room?
   **/
  private function isItemInContainerInRoom($itemInQuestion) {
    $room = GameState::getGameState()->getPlayerRoom();
    foreach ($room->items as $itemName => $item)
    {
      if (is_a($item, "\playable\IContainer"))
      {
        if (!is_a($item, "\playable\IOpenable") || $item->isOpened())
        {
          foreach ($item->items as $containedItemName => $containedItem) {
            if ($containerItemName == $itemInQuestion)
              return $containedItem;
          }
        }
      }
    }
    return false;
  }


  public function validateCommand($commandLine)
  {
    $gameState = GameState::getGameState()->getGameState();
    $matches = array();
    if (preg_match('/\s*([\w\d$_.]+)\s*=\s*([\w\d$_.]+)\s*;/', $commandLine, $matches))
    {
      return true;
    }
    return false;
  }

  public function executeCommand($commandLine)
  {
    $gameState = GameState::getGameState()->getGameState();
    $matches = array();
    if (preg_match('/\s*([\w\d$_.]+)\s*=\s*([\w\d$_.]+)\s*;/', $commandLine, $matches))
    {
      //left of =
      $left = $matches[1];
      //where is left at? Player, Room, Locker, etc.?
      $leftContainer = "";
      if ( !($leftContainer = $this->isPlayerItem($left) !== FALSE)
        || !($leftContainer = $this->isRoomItem($left) !== FALSE)
        || !($leftContainer = $this->isItemInContainerInRoom($left) !== FALSE)
        )
      {
        return "I don't know what a $left is.";
      }
      //right of =
      $right = $matches[2];
      //where is right at? Player, Room, Locker, etc.?
      $rightContainer = "";
      if ( !($rightContainer = $this->isPlayerItem($right) !== FALSE)
        || !($rightContainer = $this->isRoomItem($right) !== FALSE)
        || !($rightContainer = $this->isItemInContainerInRoom($right) !== FALSE)
        )
      {
        return "I don't know what a $right is.";
      }
      //assign item right to left
      //remove from origin
    }
  }

}

CommandProcessor::addCommandHandler(new IAssignable_assignCommandHandler());
