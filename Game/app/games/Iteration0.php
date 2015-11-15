<?php

namespace games;

require_once __DIR__.'/../game/GameBuilder.php';
require_once __DIR__.'/../game/Direction.php';
require_once __DIR__.'/../game/Game.php';
require_once __DIR__.'/../game/Room.php';

use \game\GameBuilder;
use \game\Direction;
use \game\Game;
use \game\Room;

GameBuilder::newGame("Iteration0")
  ->insertRoom((new Room('entrance'))->define(function ($room) {
      $room->getComponent("Inspector")->onInspect(function ($inspector) {
        return 'You are standing at a castle door. A door lies to the north.';
      });
      $room->setImageUrl('background.jpg');
    }))
  ->insertRoom((new Room('hall'))->define(function ($room) {
      $room->getComponent("Inspector")->onInspect(function ($inspector) {
        return 'You are in a lavishly decorated hallway. The kitchen lies to the west, and the door to the outside is to the south.';
      });
      $room->setImageUrl('mainHall.jpg');
      // ->insertDoorObstacle('hall', Direction::$w, 'door')
  }))
  ->insertRoom((new Room('kitchen'))->define(function ($room) {
      $room->getComponent("Inspector")->onInspect(function ($inspector) {
        return 'You are in a kitchen. Someone has been cooking here lately and the smell of mutton still hangs heavy in the air. The hallway lies to the east.';
      });
      $room->setImageUrl('CastleRoom.jpg');
  }))
  ->connectRooms('entrance', Direction::$north, 'hall')
  ->connectRooms('hall', Direction::$west, 'kitchen')
  ->setSpawnPoint('entrance')
;
