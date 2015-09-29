<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';

///Handles reset command.
class VariableBagContentsHandler extends BaseCommandHandler
{
  use TUsesItems;

  ///Validates the incoming command line for reset commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'globals' ||
            $commandLine == 'locals';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine)
  {
    $commandLine = strtolower($commandLine);
    if ($commandLine == 'globals')
      return $this->inspectGlobals();
    else if ($commandLine == 'locals')
      return $this->inspectLocals() . "\n" . $this->inspectRoomContents();
  }

}

CommandProcessor::addCommandHandler(new VariableBagContentsHandler());
