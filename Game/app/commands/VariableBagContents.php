<?php

namespace commands;
use game\CommandProcessor;
use game\GameState;

require_once __DIR__.'/../game/GameState.php';
require_once __DIR__.'/../game/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

///Handles reset command.
class VariableBagContentsHandler extends BaseCommandHandler
{
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
      return $this->inspectLocals();
  }

  public function inspectLocals()
  {
    $gameState = GameState::getGameState();
    $eol = "\n";
    $output = "You have the following variables in your local variable bag:$eol";
    foreach($gameState->locals as $local => $value)
    {
      $output .= \java\JavaReflection::inspectInstance($gameState->locals[$local], $local) . $eol;
    }
    $output .= "The following variables are available to you because of where you are standing:$eol";
    foreach($gameState->getPlayerRoom()->items as $item => $value)
    {
      $output .= \java\JavaReflection::inspectInstance($gameState->getPlayerRoom()->items[$item], $item) . $eol;
    }
    return $output;
  }

  public function inspectGlobals()
  {
    $gameState = GameState::getGameState();
    $eol = "\n";
    $output = "The following variables are available anywhere in the game:$eol";
    foreach($gameState->globals as $global => $value)
    {
      $output .= \java\JavaReflection::inspectInstance($gameState->globals[$global], $global) . $eol;
    }
    return $output;
  }

}

CommandProcessor::addCommandHandler(new VariableBagContentsHandler());
