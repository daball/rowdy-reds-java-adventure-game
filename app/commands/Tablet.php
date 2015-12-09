<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;
use java\JavaReflection;

require_once 'BaseCommandHandler.php';
require_once 'TUsesItems.php';
require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once __DIR__.'/../java/JavaReflection.php';


class JavadocCommandHandler extends BaseCommandHandler
{
  private function isClassSpecified($commandLine)
  {
    return strlen($commandLine) > 8;
  }

  private function getClassName($commandLine)
  {
    return trim(substr($commandLine, 8));
  }

  ///Validates the incoming command line for help commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine, $tabletCode)
  {
    return stripos($commandLine, 'javadoc') === 0;
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine, $tabletCode)
  {
    $gameState = GameState::getInstance();
    $eol = "\n";
    if ($this->isClassSpecified($commandLine)) {
      $className = $this->getClassName($commandLine);
      if ($gameState->isKnownAPIClass($className))
      {
        $javadoc = JavaReflection::javadoc("\\playable\\".$className);
        return $javadoc;
      }
      else
      {
        return "I don't know anything about a $className.";
      }
    }
    else
      return "javadoc: work in progress";
  }
}

CommandProcessor::addCommandHandler(new JavadocCommandHandler());

class VariableBagContentsHandler extends BaseCommandHandler
{
  use TUsesItems;

  ///Validates the incoming command line for reset commands.
  ///Return true if command line is valid for this command handler.
  ///Return false if command line is not valid for this command handler.
  public function validateCommand($commandLine, $tabletCode)
  {
    $commandLine = strtolower($commandLine);
    return  $commandLine == 'globals' ||
            $commandLine == 'locals';
  }

  ///Executes the incoming command line.
  ///Return the output for the command. Do not add a newline at the
  ///end of the output.
  public function executeCommand($commandLine, $tabletCode)
  {
    $commandLine = strtolower($commandLine);
    if ($commandLine == 'globals')
      return $this->inspectGlobals();
    else if ($commandLine == 'locals')
      return $this->inspectLocals() . "\n" . $this->inspectRoomContents();
  }

}

CommandProcessor::addCommandHandler(new VariableBagContentsHandler());
