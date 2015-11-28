<?php

namespace util;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../game/Direction.php';
require_once 'PubSubMessageQueue.php';

use \engine\GameState;
use \game\Direction;

class Resolver
{
  const NO_RESULT                   = 0;

  const PLAYER                      = 1;
  const PLAYER_LEFT_HAND            = 2;
  const PLAYER_LEFT_HAND_ITEM       = 4;
  const PLAYER_RIGHT_HAND           = 8;
  const PLAYER_RIGHT_HAND_ITEM      = 16;
  const PLAYER_BACKPACK             = 32;
  const PLAYER_BACKPACK_INDEX       = 64;
  const PLAYER_BACKPACK_ITEM        = 128;
  const PLAYER_EQUIPMENT_ITEM       = 256;
  const PLAYER_ANY                  = 511;//self::PLAYER | self::PLAYER_LEFT_HAND | self::PLAYER_LEFT_HAND_ITEM | self::PLAYER_RIGHT_HAND | self::PLAYER_RIGHT_HAND_ITEM | self::PLAYER_BACKPACK | self::PLAYER_BACKPACK_ITEM | self::PLAYER_EQUIPMENT_ITEM;

  const ROOM                        = 512;
  const ROOM_DIRECTION              = 1024;
  const ROOM_ITEM                   = 2048;
  const ROOM_ANY                    = 3584; //self::ROOM | self::ROOM_DIRECTION | self::ROOM_ITEM;

  const ANY                         = 4095; //self::PLAYER_ANY | self::ROOM_ANY;

  const REGEX_PLAYER                = '/^me$/';
  const REGEX_PLAYER_LEFT_HAND      = '/^(?:me.){0,1}(leftHand)$/';
  const REGEX_PLAYER_RIGHT_HAND     = '/^(?:me.){0,1}(rightHand)$/';
  const REGEX_PLAYER_BACKPACK       = '/^(?:me.){0,1}(backPack)$/';
  const REGEX_PLAYER_BACKPACK_INDEX  = '/^(?:me.){0,1}(backPack)\[(\d+)\]$/';

  const REGEX_ROOM                  = '/^(room)$/';

  private $_result = 0;
  private $_matches = null;

  private function __construct($result, $matches=null) {
    $this->_result = $result;
    $this->_matches = $matches;
  }

  /**
   * Identifies the search result.
   **/
  public function result() {
    return $this->_result;
  }

  /**
   * Identifies the search result match.
   **/
  public function matches() {
    return $this->_matches;
  }

  /**
   * Begins a search result. Question a string and produce a Resolver instance
   * with the results. Use match() to get matches(), result() to get the result,
   * and resolve() to produce an object. Limit search results by flags, which is
   * set to ANY if nothing provided. caseSensitive is used in certain situations
   * to allow a broader search.
   **/
  public static function what($question, $flags=self::ANY, $caseSensitive=true) {

    $matches = array();
    if ($caseSensitive) $caseSensitive = ''; else $caseSensitive = 'i';

    //check simple solutions

    //left hand
    if (($flags & self::PLAYER_LEFT_HAND) && preg_match(self::REGEX_PLAYER_LEFT_HAND, $question, $matches))
      return new Resolver(self::PLAYER_LEFT_HAND, $matches);
    //right hand
    if (($flags & self::PLAYER_RIGHT_HAND) && preg_match(self::REGEX_PLAYER_RIGHT_HAND, $question, $matches))
      return new Resolver(self::PLAYER_RIGHT_HAND, $matches);
    //backpack[i]
    if (($flags & self::PLAYER_BACKPACK_INDEX) && preg_match(self::REGEX_PLAYER_BACKPACK_INDEX, $question, $matches))
      return new Resolver(self::PLAYER_BACKPACK_INDEX, $matches);
    //backpack
    if (($flags & self::PLAYER_BACKPACK) && preg_match(self::REGEX_PLAYER_BACKPACK, $question, $matches))
      return new Resolver(self::PLAYER_BACKPACK, $matches);
    //me
    if (($flags & self::PLAYER) && preg_match(self::REGEX_PLAYER, $question, $matches))
      return new Resolver(self::PLAYER, $matches);
    //room
    if (($flags & self::ROOM) && preg_match(self::REGEX_ROOM, $question, $matches))
      return new Resolver(self::ROOM, $matches);

    //check solutions against game state

    $gameState = GameState::getInstance();
    if ($gameState) {
      $player = $gameState->getPlayer();
      $leftHand = $player->getLeftHand();
      $rightHand = $player->getRightHand();
      $backpack = $player->getBackpack();
      $equipment = $player->getEquipment();
      $room = $gameState->getPlayerRoom();

      //n,s,e,w,u,d,north,south,east,west,up,down
      if (($flags & self::ROOM_DIRECTION) && ($direction = Direction::cardinalDirection($question)))
        return new Resolver(self::ROOM_DIRECTION, array($question, $direction, $room->getDirection($direction)));
      //room by name
      if (($flags & self::ROOM) && $room->getName() == $question)
        return new Resolver(self::ROOM, array($question, $room->getName(), $room));
      //nested room item by name
      if (($flags & self::ROOM_ITEM)
          && ($item = $room->getComponent('Container')->findNestedItemByName($question))
          && $item->getName() == $question
      ) return new Resolver(self::ROOM_ITEM, array($question, $item->getName(), $item));
      //nested leftHand item by name
      if (($flags & self::PLAYER_LEFT_HAND_ITEM)
          && ($item = $leftHand->getComponent('Container')->findNestedItemByName($question))
          && $item->getName() == $question
      ) return new Resolver(self::PLAYER_LEFT_HAND_ITEM, array($question, $item->getName(), $item));
      //nested rightHand item by name
      if (($flags & self::PLAYER_RIGHT_HAND_ITEM)
          && ($item = $rightHand->getComponent('Container')->findNestedItemByName($question))
          && $item->getName() == $question
      ) return new Resolver(self::PLAYER_RIGHT_HAND_ITEM, array($question, $item->getName(), $item));
      if (($flags & self::PLAYER_BACKPACK_ITEM)
          && ($backpack)
          && ($item = $backpack->getComponent('Container')->findNestedItemByName($question))
          && $item->getName() == $question
      ) return new Resolver(self::PLAYER_BACKPACK_ITEM, array($question, $item->getName(), $item));
      if (($flags & self::PLAYER_EQUIPMENT_ITEM)
          && ($equipment)
          && ($item = $equipment->getComponent('Container')->findItemByName($question))
          && $item->getName() == $question
      ) return new Resolver(self::PLAYER_EQUIPMENT_ITEM, array($question, $item->getName(), $item));
    }
    return new Resolver(self::NO_RESULT);
  }

  public function resolve() {
    $gameState = GameState::getInstance();
    if ($gameState) {
      $player = $gameState->getPlayer();
      $leftHand = $player->getLeftHand();
      $rightHand = $player->getRightHand();
      $backpack = $player->getBackpack();
      $room = $gameState->getPlayerRoom();

      switch ($this->_result) {
        //simple resolution of placeholders
        case self::PLAYER:
          return $player;
        case self::PLAYER_LEFT_HAND:
          return $leftHand;
        case self::PLAYER_RIGHT_HAND:
          return $rightHand;
        case self::PLAYER_BACKPACK:
          return $backpack;
        case self::ROOM:
          return $room;
        case self::ROOM_DIRECTION:
        case self::ROOM_ITEM:
        case self::PLAYER_LEFT_HAND_ITEM:
        case self::PLAYER_RIGHT_HAND_ITEM:
        case self::PLAYER_BACKPACK_ITEM:
          return $this->matches()[2];
        case self::NO_RESULT:
        default:
          return null;
      }
    }
  }

  public function resolveHandContents($whichHand) {
    $item = null;
    $gameState = GameState::getInstance();
    if ($gameState) {
      $player = $gameState->getPlayer();
      $rightHand = null;
      if ($whichHand & Resolver::PLAYER_LEFT_HAND) {
        $hand = $player->getLeftHand();
      }
      else if ($whichHand & Resolver::PLAYER_RIGHT_HAND) {
        $hand = $player->getRightHand();
      }
      if ($hand && $hand->getComponent('Container')->hasItemAt(0)) {
        $item = $hand->getComponent('Container')->getItemAt(0);
      }
      return $item;
    }
    return self::NO_RESULT;
  }
}
