<?php

namespace games;

require_once __DIR__.'/../app/game/GameBuilder.php';
require_once __DIR__.'/../app/game/Direction.php';
require_once __DIR__.'/../app/game/Game.php';
require_once __DIR__.'/../app/game/Room.php';

use \game\GameBuilder;
use \game\Direction;
use \game\Game;
use \game\Room;

/* SIMPLE ROOM DEFINITIONS */
$gameName = pathinfo(__FILE__)['filename'];
$castleEntrance = array(
  'name'        => "Castle Entrance",
  'description' => "You are standing at a castle door. A door lies to the north.",
  'imageUrl'    => "background.jpg",
);
$mainHall = array(
  'name'        => "Main Hall",
  'description' => "You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.",
  'imageUrl'    => "mainHall.jpg",
  'items'       => array(
    'door'        => array(
      'type'        => "door",
      'name'        => "door",
      'direction'   => Direction::$w,
    )
  )
);
$kitchen = array(
  'name'        => "Kitchen",
  'description' => "You are in a kitchen. Someone has been cooking here lately and the smell of mutton still hangs heavy in the air. The hallway lies to the east.",
  'imageUrl'    => "CastleRoom.jpg",
);

/* BUILD GAME */
GameBuilder::newGame($gameName)
  ->insertRoom(                                       \game\assembleRoom($castleEntrance))
  ->insertRoomAt($castleEntrance, Direction::$north,  \game\assembleRoom($mainHall))
  ->insertRoomAt($mainHall,       Direction::$west,   \game\assembleRoom($kitchen))
  ->setSpawnPoint($castleEntrance)
;
