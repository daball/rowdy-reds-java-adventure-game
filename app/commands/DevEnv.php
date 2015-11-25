<?php

namespace commands;

require_once __DIR__.'/../engine/GameEngine.php';
require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/Router.php';

use engine\GameEngine;
use engine\GameState;
use engine\Router;

if (GameEngine::isApplicationEnv('development')) {

  //assign: target = source;
  Router::route('/^\s*goto\s*(.*)$/', function ($command, $code, $pattern, $matches) {
    $listAllRooms = function () {
      $gameState = GameState::getInstance();
      $output = "The following rooms exist in the game:\n";
      foreach ($gameState->getGame()->getAllRooms() as $room) {
        $output .= "  " . $room->getName() . "\n";
      }
      return $output;
    };
    if (GameEngine::isApplicationEnv('development')) {
      $gameState = GameState::getInstance();
      $gotoWhere = $matches[1];
      if ($gotoWhere === "") {
        $output = "DEVELOPMENT MODE ONLY:  ";
        $output .= "Type goto [room name] in order to inspert player into that room, despite any game rules.\n";
        $output .= $listAllRooms();
        return $output;
      }
      else if ($room = $gameState->getGame()->getRoom($gotoWhere)) {
        $gameState->getPlayer()->setLocation($room->getName());
        return "DEVELOPMENT MODE ONLY:  "
              . "You have been inserted into $gotoWhere, despite any game rules.\n"
              . $gameState->inspectRoom();
      }
      else
        return "DEVELOPMENT MODE ONLY:  "
              . "$gotoWhere does not exist in the game.\n"
              . $listAllRooms();
    }
  });
}
