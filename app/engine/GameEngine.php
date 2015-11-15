<?php

namespace engine;

require_once 'GameState.php';
require_once 'CommandProcessor.php';
require_once __DIR__.'/../commands/index.php';
require_once __DIR__.'/../game/GameBuilder.php';

use \game\GameBuilder;

/**
 *  GameEngine manages the game state across many page loads
 *  using PHP sessions. Typically sessions work by sending out
 *  a session cookie, which restores the session from the PHP
 *  session backing store (which is sometimes a session database).
 *  GameEngine also dispatches command line input to command line
 *  handlers and updates the game state when command is done executing.
 **/
class GameEngine
{
  protected $gameState;
  protected $commandProcessor;

  public function createSession($gameName)
  {
    $this->gameState = GameState::getInstance($gameName);
  }

  public function restoreSession($session)
  {
    $this->gameState = GameState::getInstance($session);
  }

  public function saveSession()
  {
    $_SESSION["gameState_" . $this->getGameState()->getGame()->getName()] = serialize(GameState::getInstance());
  }

  public function getGameState()
  {
    return $this->gameState;
  }

  public function getCommandProcessor()
  {
    return $this->commandProcessor;
  }

  public static function getValidGames()
  {
    $validGames = scandir(__DIR__.'/../../games', SCANDIR_SORT_ASCENDING);
    $validGames = array_filter($validGames, function ($game) {
      return (substr($game, 0, 1) !== '.'
              && strtolower(substr($game, strlen($game)-4)) === '.php');
    });
    $validGames = array_map(function ($game) {
      return (substr($game, 0, strrpos($game, '.')));
    }, $validGames);
    return array_values($validGames);
  }

  public static function isValidGame($gameName)
  {
    return in_array($gameName, self::getValidGames());
  }

  public static function loadGame($gameName)
  {
    if (self::isValidGame($gameName)) {
      require_once __DIR__.'/../../games/'.$gameName.'.php';
      return GameBuilder::getNamedGame($gameName);
    }
  }

  public function __construct($gameName)
  {
    //check if existing gameState exists in session
    if (isset($_SESSION["gameState_$gameName"]))
      //if so, restore the gameState session
      $this->restoreSession($_SESSION["gameState_$gameName"]);
    else
      //otherwise, create a new gameState session
      $this->createSession($gameName);
    //create command processor, which will execute any command on the $_POST['commandLine']
    $this->commandProcessor = new CommandProcessor();
  }
}
