<?php

namespace engine;

require_once 'GameState.php';
require_once 'CommandProcessor.php';
require_once __DIR__.'/../commands/index.php';

///GameEngine manages the game state across many page loads
///using PHP sessions. Typically sessions work by sending out
///a session cookie, which restores the session from the PHP
///session backing store (which is sometimes a session database).
///GameEngine also dispatches command line input to command line
///handlers and updates the game state when command is done executing.
class GameEngine
{
  protected $gameState;
  protected $commandProcessor;

  public function createSession()
  {
    echo "createSession() hit";
    $this->gameState = GameState::getGameState();
  }

  public function restoreSession()
  {
    echo "restoreSession() hit";
    $this->gameState = GameState::getGameState($_SESSION['gameState']);
  }

  public function saveSession()
  {
    echo "saveSession() hit";
    $_SESSION['gameState'] = $this->gameState->serialize();
  }

  public function getGameState()
  {
    return $this->gameState;
  }

  public function getCommandProcessor()
  {
    return $this->commandProcessor;
  }

  public function __construct()
  {
    //check if existing gameState exists in session
    if (isset($_SESSION['gameState']))
      //if so, restore the gameState session
      $this->restoreSession();
    else
      //otherwise, create a new gameState session
      $this->createSession();
    //create command processor, which will execute any command on the $_POST['commandLine']
    $this->commandProcessor = new CommandProcessor();
  }
}
