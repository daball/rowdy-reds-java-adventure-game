<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

///Handles inspect command.
class InspectCommandHandler extends BaseCommandHandler
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
    $gameState = GameState::getGameState();
    $inspectWhat = $this->getTargetName($commandLine);
    echo $inspectWhat;
    if ($inspectWhat === "")
      //no parameters, inspect the room
      return $gameState->getPlayerRoom()->inspect() . "\n\n" . $this->inspectRoomContents();
    else {

      if (($item = $this->isPlayerItem($inspectWhat)) !== FALSE) {
        if ($item === null)
          return "Your hand is empty.";
        else if (is_a($item, '\playable\IInspectable'))
          return $item->inspect();
        else
          return "The item in your hand is not inspectable.";
      }
      else if (($item = $this->isRoomItem($inspectWhat)) !== FALSE) {
        if (is_a($item, '\playable\IInspectable'))
          return $item->inspect();
        else
          return "The item in the room is not inspectable.";
      }
      else if (($item = $isItemInContainerInRoom($itemInQuestion)) !== FALSE) {
        if (is_a($item, '\playable\IInspectable'))
          return $item->inspect();
        else
          return "The item in the room is not inspectable.";
      }
      else {
        return "I don't know what an $inspectWhat is.";
      }
    }
  }
}

CommandProcessor::addCommandHandler(new InspectCommandHandler());
