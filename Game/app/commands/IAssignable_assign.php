<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

class IAssignable_assignCommandHandler extends BaseCommandHandler
{
  use TUsesItems;

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
      if ( !(($leftContainer = $this->isPlayerItem($left)) !== FALSE)
        && !(($leftContainer = $this->isRoomItem($left)) !== FALSE)
        && !(($leftContainer = $this->isItemInContainerInRoom($left)) !== FALSE)
        )
      {
        return "I don't know what a $left is.";
      }
      //right of =
      $right = $matches[2];
      //where is right at? Player, Room, Locker, etc.?
      $rightContainer = "";
      if ( !(($rightContainer = $this->isPlayerItem($right)) !== FALSE)
        && !(($rightContainer = $this->isRoomItem($right)) !== FALSE)
        && !(($rightContainer = $this->isItemInContainerInRoom($right)) !== FALSE)
        )
      {
        return "I don't know what a $right is.";
      }
      if ($this->isPlayerItem($left) !== FALSE)
      {
        if ($this->isPlayerItem($right) !== FALSE)
        {
          if ($left == $right) {
            return "Your $left is already in your hand.";
          }
          else if (substr($left, -8) === "leftHand") {
            $gameState->getPlayer()->leftHand = $rightContainer;
            $gameState->getPlayer()->rightHand = $leftContainer;
            return "You swapped the contents of your hands.";
          }
          else if (substr($left, -9) === "rightHand") {
            $gameState->getPlayer()->rightHand = $rightContainer;
            $gameState->getPlayer()->leftHand = $leftContainer;
            return "You swapped the contents of your hands.";
          }
        }
        else if ($this->isRoomItem($right) !== FALSE)
        {
          if (is_a($rightContainer, "\playable\IAssignable")) {
            if (substr($left, -8) === "leftHand")
            {
              $rightContainer->assign($right, $this->getPlayerRoom(), $gameState->getPlayer()->leftHand);
            }
            else if (substr($left, -9) === "rightHand")
            {
              $rightContainer->assign($right, $this->getPlayerRoom(), $gameState->getPlayer()->leftHand);
            }
            //this should be done in TAssignable::assign() now:
            //$gameState->getPlayerRoom()->removeItem($right);
            $whichHand = (substr($left, -9) === "rightHand" ? "right hand" : "left hand");
            return "You grabbed the $right and put it in your $whichHand.";
          }
          else
            return "You can't pick up the $right.";
        }
        else if ($this->isItemInContainerInRoom($right) !== FALSE)
        {
          $room = GameState::getGameState()->getPlayerRoom();
          foreach ($room->getAllItems() as $itemName => $item)
          {
            if (is_a($item, "\playable\IContainer") && (!is_a($item, "\playable\IOpenable") || $item->isOpened()))
            {
              foreach ($item->getAllItems() as $containedItemName => $containedItem) {
                if ($containerItemName == $itemInQuestion) {
                  if (is_a($rightContainer, "\playable\IAssignable"))
                  {
                    $leftContainer = $rightContainer;
                    $item->removeItem($right);
                    $whichHand = (substr($left, -9) === "rightHand" ? "right hand" : "left hand");
                    return "You grabbed the $right from the $item and put it in your $whichHand.";
                  }
                  else
                    return "You can't pick up the $right.";
                }
              }
            }
          }
        }
        else
          return "I don't know what a $right is.";
      }
      else if ($this->isRoomItem($left) !== FALSE)
      {
        $room = GameState::getGameState()->getPlayerRoom();
        if (is_a($room->getItem($left), "\playable\IContainer")) {
          if ($this->isPlayerItem($right) !== FALSE)
          {
            $output = "";
            if (substr($right, -8) === "leftHand")
            {
              $output = $room->setItem($left, $gameState->getPlayer()->leftHand);
              $gameState->getPlayer()->leftHand = null;
            }
            else if (substr($right, -9) === "rightHand")
            {
              $output = $room->setItem($left, $gameState->getPlayer()->rightHand);
              $gameState->getPlayer()->rightHand = null;
            }
            var_dump($output);
            return $output;
          }
          else {
            return "You must pick it up first.";
          }
        }
      }
      else {
        return "I don't know what to do.";
      }
    }
  }

}

CommandProcessor::addCommandHandler(new IAssignable_assignCommandHandler());
