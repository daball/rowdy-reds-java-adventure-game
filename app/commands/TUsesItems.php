<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';

use \engine\GameState;
use \ReflectionClass;

trait TUsesItems
{
  private function getItem($itemName) {
    if (($item = $this->getPlayerItem($itemName)) != null)
      return $item;
    if (($item = $this->getRoomItem($itemName)) != null)
      return $item;
  }

  /**
   * Is this an object assigned to the player?
   **/
  private function getPlayerItem($itemName) {
    $gameState = GameState::getInstance();
    $player = $gameState->getPlayer();
    $leftHand = $player->getLeftHand();
    $rightHand = $player->getRightHand();
    $backpack = $player->getBackpack();
    if ($itemName == "me.leftHand" || $itemName == "leftHand")
      return $player->getLeftHand();
    if ($itemName == "me.rightHand" || $itemName == "rightHand")
      return $player->getRightHand();
    if ($itemName == "me.backpack" || $itemName == "backpack")
      return $player->getBackpack();
    // if ()
    return null;
  }

  /**
   * Is this an object contained in the room?
   **/
  private function getRoomItem($itemName) {
    $gameState = GameState::getInstance();
    $room = GameState::getInstance()->getPlayerRoom();
    $container = $room->getComponent('Container');
    return $container->findNestedItemByName($itemName);
  }

  /**
   * Is this an object contained in the room?
   **/
  private function getRoomDirection($itemName) {
    $gameState = GameState::getInstance();
    $room = GameState::getInstance()->getPlayerRoom();
    $container = $room->getComponent('Container');
    return $container->findNestedItemByName($itemName);
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
  }
}
