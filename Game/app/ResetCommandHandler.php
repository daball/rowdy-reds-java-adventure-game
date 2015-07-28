<?php

require_once 'CommandHandlerInterface.php';

///Handles reset command.
class HelpCommandHandler extends CommandHandlerInterface
{
  ///Validates the incoming command line for help commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($gameState, $commandLine)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'reset' ||
            $commandLine == 'restart';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($gameState, $commandLine)
  {
    $message = $gameState->resetGameState();
    $gameState->commandHistory = "";
    return $message;
  }
}
