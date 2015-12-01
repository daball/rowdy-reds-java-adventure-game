<?php

namespace commands;

require_once __DIR__.'/../engine/Router.php';
require_once __DIR__.'/../engine/GameEngine.php';
require_once __DIR__.'/../engine/GameState.php';

use \engine\Router;
use \engine\GameEngine;
use \engine\GameState;

//exit command
Router::route('/^\s*(exit|System.exit\(\d+\);)\s*$/i', function ($command, $code, $pattern, $matches) {
  $gameState = GameState::getInstance();
  $gameState->isExiting(true);
  return "Thank you for playing!\nWelcome back!";
});

//exit command
Router::route('/^\s*(manual|man)\s*$/i', function ($command, $code, $pattern, $matches) {
  $gameState = GameState::getInstance();
  $gameState->showManual(true);
  return "The user manual opens and you can read how to play.";
});

//exit command
Router::route('/^\s*(reset|restart)\s*$/i', function ($command, $code, $pattern, $matches) {
  $gameState = GameState::getInstance()->resetGameState();
});

//navigation commands
Router::route(array(
  '/^\s*(north|south|east|west|up|down|n|s|e|w|u|d|)\s*$/i',
  '/^(?:me.){0,1}(?:move)(Up|Down|North|South|East|West)(?:\(\);)$/'
  ), function ($command, $code, $pattern, $matches) {
    $gameState = GameState::getInstance();
    $gameState->incrementMoves();
    $direction = $matches[1];
    return $gameState->getPlayer()->navigate($direction);
});

//help command
Router::route('/^\s*(help|\?)\s*(.*)$/i', function ($command, $code, $pattern, $matches) {
  $eol = "\n";
  $isDevMode = GameEngine::isApplicationEnv("development");
  $devCommands = "DEVELOPMENT MODE ONLY:$eol"
                ."goto             - list all rooms you can go to$eol"
                ."goto room name   - enter a room, despite any game logic$eol$eol";

  return  "Rowdy Red's Java Adventures" . $eol
        . $eol
        . "Goal: Explore our tiny castle and battle the dragon if you dare." . $eol
        . $eol
        . ($isDevMode?$devCommands:"")
        . "User interface commands:" . $eol
        . "  ?     | help            - Display this help screen" . $eol
        . "  man   | manual          - Displays the user manual in a lightbox" . $eol
        . "  reset | restart         - Restart the game" . $eol
        . "  exit  | System.exit(0); - Exit the game" . $eol
        . $eol
        . "Navigation commands:" . $eol
        . "  u | up    | moveUp();    | me.moveUp();    - Navigate up" . $eol
        . "  w | west  | moveWest();  | me.moveWest();  - Navigate west" . $eol
        . "  n | north | moveNorth(); | me.moveNorth(); - Navigate north" . $eol
        . "  s | south | moveSouth(); | me.moveSouth(); - Navigate south" . $eol
        . "  e | east  | moveEast();  | me.moveEast();  - Navigate east" . $eol
        . "  d | down  | moveDown();  | me.moveDown();  - Navigate down" . $eol
        . $eol
        // . "New experimental commands:" . $eol
        // . "  javadoc             - List known API classes in your JavaDoc notebook$eol"
        // . "  javadoc ClassName   - Display JavaDoc documentation about the API class$eol"
        // . "  locals              - Display list of variables in your variable bag$eol"
        // . "  gc                  - Garbage collect a local variable the player has made$eol"
        // . "  globals             - List variables available anywhere in the game$eol"
        // . $eol
        . "Game commands:" . $eol
        . "  target = item;                - Assign an item to a target container$eol"
        . "  inspect [room]|item|direction - Inspects the room, an item, or a direction$eol"
        . "  item.open();                  - Open an item$eol"
        . "  item.close();                 - Close an item$eol"
        . "  item.unlock(key|hand);        - Unlock an item with a key in your hand$eol"
        . "  item.lock(key|hand);          - Lock an item with a key in your hand$eol"
        . "  ClassName = new ClassName();  - Instantiate an API class to a local variable$eol"
        . $eol
        . "Player items:" . $eol
        . "  leftHand                     - Player's left hand$eol"
        . "  rightHand                    - Player's right hand$eol"
        . "  backpack                     - Player's backpack, if equipped$eol"
        ;
});
