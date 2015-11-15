<?php

namespace commands;
use engine\GameState;
use \ReflectionClass;

require_once __DIR__.'/../engine/GameState.php';

trait TUsesItems
{
  /**
   * Is this an object assigned to the player?
   **/
  private function isPlayerItem($itemInQuestion) {
    if ($itemInQuestion == "me.leftHand" || $itemInQuestion == "leftHand")
      return GameState::getInstance()->getPlayer()->leftHand;
    else if ($itemInQuestion == "me.rightHand" || $itemInQuestion == "rightHand")
      return GameState::getInstance()->getPlayer()->rightHand;
    else return false;
  }

  /**
   * Is this an object contained in the room?
   **/
  private function isRoomItem($itemInQuestion) {
    $room = GameState::getInstance()->getPlayerRoom();
    foreach ($room->getAllItems() as $itemName => $item)
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
    $room = GameState::getInstance()->getPlayerRoom();
    foreach ($room->getAllItems() as $itemName => $item)
    {
      if (is_a($item, "\playable\IContainer"))
      {
        if (!is_a($item, "\playable\IOpenable") || $item->isOpened())
        {
          foreach ($item->getAllItems() as $containedItemName => $containedItem) {
            if ($containerItemName == $itemInQuestion)
              return $containedItem;
          }
        }
      }
    }
    return false;
  }

  public function inspectLocals()
  {
    $gameState = GameState::getInstance();
    $eol = "\n";
    $output = "You have the following variables in your local variable bag:$eol";
    foreach($gameState->locals as $local => $value)
    {
      //$output .= \java\JavaReflection::inspectInstance($gameState->locals[$local], $local) . $eol;
      $class = new ReflectionClass($value);
      $className = $class->getShortName();
      $output .= "$className $local";
    }
    return $output;
  }

  public function inspectRoomContents()
  {
    $gameState = GameState::getInstance();
    $eol = "\n";
    $output = "The following variables are available to you because of where you are standing:$eol";
    foreach($gameState->getPlayerRoom()->getAllItems() as $item => $value)
    {
      //$output .= \java\JavaReflection::inspectInstance($value, $item) . $eol;
      $class = new ReflectionClass($value);
      $className = $class->getShortName();
      $output .= "$className $item";
    }
    return $output;
  }

  public function inspectGlobals()
  {
    $gameState = GameState::getInstance();
    $eol = "\n";
    $output = "The following variables are available anywhere in the game:$eol";
    foreach($gameState->globals as $global => $value)
    {
      //$output .= \java\JavaReflection::inspectInstance($gameState->globals[$global], $global) . $eol;
      $class = new ReflectionClass($value);
      $className = $class->getShortName();
      $output .= "$className $global";
    }
    return $output;
  }}
