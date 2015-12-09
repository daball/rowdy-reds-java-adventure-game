<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/Router.php';
require_once __DIR__.'/../util/Resolver.php';
require_once __DIR__.'/../java/TabletCompilerService.php';
require_once __DIR__.'/../playable/Key.php';

use \engine\GameState;
use \engine\Router;
use \util\Resolver;
use \TabletCompilerService;
use \playable\Key;

//reusable no matching result output
function noResult($provided) {
  $provided = insertAOrAn($provided);
  return "I don't know what $provided is.";
}

/**
   * Source: http://php.net/manual/en/function.ucwords.php
   * Convert string to in camel-case, useful for class name patterns.
   *
   * @param $string
   *   Target string.
   *
   * @return string
   *   Camel-case string.
   */
function toCamelCase($string){
    $string = str_replace('-', ' ', $string);
    $string = str_replace('_', ' ', $string);
    $string = ucwords(strtolower($string));
    $string = str_replace(' ', '', $string);
    return lcfirst($string);
}

/* Assignable */

Router::route('/^\s*([\w\d$_.\[\]]+)\s*=\s*new\s+([\w\d$_.\[\]]+)\s*\(([\w\d\s$_.\-,\[\]\"\']*)\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $target = $matches[1];
  $cls = $matches[2];
  $params = $matches[3];
  $index = -1; //index in container to assign, this is variable for backpack

  $allowedClasses = array("Key");
  if (!in_array($cls, $allowedClasses))
    return "You cannot instantiate " . insertAOrAn($cls) . ".";

  if (!$params || strlen($params) < 2 || substr($params, 0, 1) != "\"" || substr($params, -1) != "\"")
    return "You must pass in a plaintext secret as a String in order to instantiate " . insertAOrAn($cls) . ".";

  $targetResolver = Resolver::what($target);
  switch ($targetResolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($target);
    case Resolver::PLAYER_BACKPACK:
    case Resolver::PLAYER_BACKPACK_INDEX:
      if (!GameState::getInstance()->getPlayer()->hasEquipmentItem("backpack"))
        return noResult($target);
      $target = GameState::getInstance()->getPlayer()->getBackpack();
      if ($targetResolver->result() == Resolver::PLAYER_BACKPACK_INDEX) {
        $index = $targetResolver->resolveBackpackIndex();
      }
      break;
    default:
      $target = $targetResolver->resolve();
  }
  if ($target->getName() == "backpack" && !GameState::getInstance()->getPlayer()->hasEquipmentItem("backpack"))
    return "You must equip the " . $target->getName() . " first.";
  if ($targetResolver->result() == Resolver::PLAYER_BACKPACK)
    return "You must use the backpack like an array, as in backpack[0], backpack[1], and so on. The backpack will only hold 5 items.";

  $secret = trim($params, "\"");
  $keyName = toCamelCase($secret . " Key");
  $key = new Key($keyName, $secret);

  $yoursOrTheirs = "the";
  $indexText = "";
  switch ($targetResolver->result()) {
    case Resolver::PLAYER_BACKPACK_INDEX:
      $indexText = " at slot $index";
      //don't break yet, keep on trucking
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $yoursOrTheirs = "your";
  }

  //pre-instantiation check
  if (!$key->hasComponent('Assignable'))
    return $source->getName() . " is not an assignable item.";
  if (!$target->hasComponent('Container'))
    return $target->getName() . " is no place for " . insertAOrAn($source->getName()) . ".";

  GameState::getInstance()->incrementMoves();
  return "You have instantianted a new $cls called $keyName with the secret $secret.  "
        . $target->getComponent("Container")->setItemAt($index, $key)
        . $key->getComponent('Assignable')->assignTo($target, $index);

});

//assign: target = source;
Router::route('/^\s*([\w\d$_.\[\]]+)\s*=\s*([\w\d$_.\[\]]+)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $target = $matches[1];
  $source = $matches[2];
  $index = -1; //index in container to assign, this is variable for backpack

  $sourceResolver = Resolver::what($source);
  if ($sourceResolver->result() == Resolver::NO_RESULT)
    return noResult($source);
  $source = $sourceResolver->resolve();

  $targetResolver = Resolver::what($target);
  switch ($targetResolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($target);
    case Resolver::PLAYER_BACKPACK:
    case Resolver::PLAYER_BACKPACK_INDEX:
      if (!GameState::getInstance()->getPlayer()->hasEquipmentItem("backpack"))
        return noResult($target);
      $target = GameState::getInstance()->getPlayer()->getBackpack();
      if ($targetResolver->result() == Resolver::PLAYER_BACKPACK_INDEX) {
        $index = $targetResolver->resolveBackpackIndex();
      }
      break;
    default:
      $target = $targetResolver->resolve();
  }
  if ($target->getName() == "backpack" && !GameState::getInstance()->getPlayer()->hasEquipmentItem("backpack"))
    return "You must equip the " . $target->getName() . " first.";
  if ($source->getName() == "backpack" && !GameState::getInstance()->getPlayer()->hasEquipmentItem("backpack"))
    return "You must equip the " . $target->getName() . " first.";
  if ($targetResolver->result() == Resolver::PLAYER_BACKPACK
    || $sourceResolver->result() == Resolver::PLAYER_BACKPACK)
    return "You must use the backpack like an array, as in backpack[0], backpack[1], and so on. The backpack will only hold 5 items.";

  //special condition: when both sides of the = are the same item name within the same container
  if ($target->getName() == $source->getName()) {
    if ( (!$target->getContainer() && !$source->getContainer()) //neither are in a container, unlikely
      || ($target->getContainer() && $source->getContainer() && $target->getContainer()->getName() == $source->getContainer()->getName())) //same item name, same container
      return "It would seem " . insertAOrAn($target->getName()) . " is already there.";
  }

  //special condition: when both sides of the = are leftHand/rightHand values
  if ($sourceResolver->result() == Resolver::PLAYER_LEFT_HAND  && $targetResolver->result() == Resolver::PLAYER_RIGHT_HAND
    ||$sourceResolver->result() == Resolver::PLAYER_LEFT_HAND  && $targetResolver->result() == Resolver::PLAYER_RIGHT_HAND_ITEM
    ||$sourceResolver->result() == Resolver::PLAYER_RIGHT_HAND && $sourceResolver->result() == Resolver::PLAYER_LEFT_HAND
    ||$sourceResolver->result() == Resolver::PLAYER_RIGHT_HAND && $sourceResolver->result() == Resolver::PLAYER_LEFT_HAND_ITEM
    ) {
      $targetHand = $target;
      if ($sourceResolver->result() == Resolver::PLAYER_RIGHT_HAND_ITEM || $sourceResolver->result() == Resolver::PLAYER_RIGHT_HAND_ITEM)
        while ($targetHand->getName() != "leftHand" || $targetHand->getName() != "rightHand")
          $targetHand = $targetHand->getContainer();
      //special condition: when both hands are empty
      if (!$source->getComponent('Container')->countItems() && !$targetHand->getComponent('Container')->countItems())
        return "Rowdy Red is happy and Rowdy Red knows it!";
      //normal condition: when hands have different contents
      $left = Resolver::what('leftHand')->resolve()->getComponent('Container');
      $leftItem = $left->countItems() ? $left->getItemAt(0) : null;
      $right = Resolver::what('rightHand')->resolve()->getComponent('Container');
      $rightItem = $right->countItems() ? $right->getItemAt(0) : null;
      if ($leftItem) $left->unsetItemAt(0);
      if ($rightItem) $right->unsetItemAt(0);
      $left->setItemAt(0, $rightItem);
      $right->setItemAt(0, $leftItem);
      return "The contents of your hands were swapped.";
  }

  //resolve item if leftHand or rightHand is the source
  switch ($sourceResolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($sourceResolver->result());
      if (!$itemInHand)
        return "Your " . $source->getName() . " is empty.";
      else
        $source = $itemInHand;
  }

  //pre-assignment check
  if (!$source->hasComponent('Assignable'))
    return $source->getName() . " is not an assignable item.";
  if (!$target->hasComponent('Container'))
    return $target->getName() . " is no place for " . insertAOrAn($source->getName()) . ".";
  //perform assignment
  GameState::getInstance()->incrementMoves();
  return $source->getComponent('Assignable')->assignTo($target, $index);
});

/* Equippable */

//me.equip(equipment);
Router::route('/^\s*(?:me\s*.\s*){0,1}equip\s*\(\s*([\w$_]*[\w\d$_\.]*)\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  $item = $resolution;
  switch ($resolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($provided);
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $hand = ($resolver->result() == Resolver::PLAYER_LEFT_HAND
                ? $resolution
                  : ($resolver->result() == Resolver::PLAYER_RIGHT_HAND
                    ? $resolution
                      : null));
      if ($hand->getComponent('Container')->hasItemAt(0))
        $item = $hand->getComponent('Container')->getItemAt(0);
      else
        return "You cannot do that, your " . $hand->getName() . " is empty.";
      break;
    case Resolver::PLAYER_BACKPACK_INDEX:
      $index = $matches[2];
      if ($resolution) {
        if ($search = $resolution->hasItemAt($index))
          $item = $resolution->getItemAt($index);
        else
          return "You don't have anything in the $index slot in your " . $resolution->getName() . ".";
      }
      else
        return "You cannot do that, your " . $resolution->getName() . " is empty.";
  }
  //pre-assignment check
  if (!$item->hasComponent('Equippable'))
    return "You cannot wear " . insertAOrAn($item->getName()) . ".  You might try carrying it instead.";

  //perform equip
  GameState::getInstance()->incrementMoves();
  return GameState::getInstance()->getPlayer()->equipItem($item);
});

/* Inspector */

//inspect|look object || [me.]inspect(object);
Router::route(array(
    '/^\s*(?:(?:read)|(?:look at))\s+(.+)$/i',
    '/^(?:me\s*.\s*){0,1}\s*(?:inspect\s*\(\s*)([\w$_]*[\w\d$_]*)(?:\s*\)\s*;\s*)$/',
    '/^\s*(?:(?:inspect)|(?:look))\s+(.*)$/i',
    '/^\s*(?:(?:inspect)|(?:look))$/i',
    '/^\s*System\s*.\s*out\s*.\s*print(?:ln){0,1}\s*\(\s*([\w$_]+[\w\d$_]*)\s*\)\s*;\s*$/',
  ), function ($command, $code, $pattern, $matches) {
  $provided = isset($matches[1]) ? $matches[1] : "";
  if ($provided == "") $provided = 'room';
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  switch ($resolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($provided);
    case Resolver::PLAYER_LEFT_HAND_ITEM:
    case Resolver::PLAYER_RIGHT_HAND_ITEM:
    case Resolver::PLAYER_BACKPACK_ITEM:
      $provided = $resolution->getName();
      $inspector = $resolution->getComponent('Inspector');
      $where = $resolution->getContainer()->getName();
      if ($resolver->result() == Resolver::PLAYER_BACKPACK_ITEM) {
        $index = $resolver->resolveBackpackIndex();
        return "The $provided is in slot $index of your $where.  " . $inspector->inspect();
      }
      else
        return "The $provided is in your $where.  " . $inspector->inspect();
    case Resolver::PLAYER_EQUIPMENT_ITEM:
      $player = GameState::getInstance()->getPlayer();
      return "You have equipped the $provided.  " . $player->getEquipmentItem($provided)->getComponent('Inspector')->inspect();
    case Resolver::PLAYER:
      return "What? You are Rowdy Red! (TODO: Make Player a GameObject. Add Inspector override.)";
    // case Resolver::PLAYER_LEFT_HAND:
    // case Resolver::PLAYER_RIGHT_HAND:
    // case Resolver::PLAYER_BACKPACK:
    case Resolver::ROOM:
      return GameState::getInstance()->inspectRoom();
    // case Resolver::ROOM_DIRECTION:
    // case Resolver::ROOM_ITEM:
    default:
      $inspector = $resolution->getComponent('Inspector');
      return $resolution->getComponent('Inspector')->inspect();
  }
});

/* Lockable */

//object.lock(key);
Router::route('/^\s*([\w$_]+[\w\d$_]*)\s*\.\s*lock\s*\(\s*([\w$_]*[\w\d$_\.]*)\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $keyProvided = $matches[2];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $keyResolver = Resolver::what($keyProvided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  $keyResolution = $keyResolver->resolve();
  if ($resolver->result() == Resolver::NO_RESULT)
    return noResult($provided);
  //resolve item if leftHand or rightHand is the source
  else switch ($resolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($resolver->result());
      if (!$itemInHand)
        return "Your " . $keyResolution->getName() . " is empty.";
      else
        $resolution = $itemInHand;
  }
  switch ($keyResolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($keyProvided);
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $keyInHand = Resolver::resolveHandContents($keyResolver->result());
      if (!$keyInHand)
        return "Your " . $keyResolution->getName() . " is empty.";
      else
        $keyResolution = $keyInHand;
  }
  if (!$resolution->hasComponent('Lockable'))
    return "You cannot lock " . $resolution->getName() . ".";
  GameState::getInstance()->incrementMoves();
  return $resolution->getComponent('Lockable')->lock($keyResolution);
});

//object.lock(key);
Router::route('/^\s*([\w$_]+[\w\d$_]*)\s*\.\s*unlock\s*\(\s*([\w$_]*[\w\d$_\.]*)\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $keyProvided = $matches[2];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $keyResolver = Resolver::what($keyProvided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  $keyResolution = $keyResolver->resolve();
  if ($resolver->result() == Resolver::NO_RESULT)
    return noResult($provided);
  //resolve item if leftHand or rightHand is the source
  else switch ($resolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($resolver->result());
      if (!$itemInHand)
        return "Your " . $keyResolution->getName() . " is empty.";
      else
        $resolution = $itemInHand;
  }
  switch ($keyResolver->result()) {
    case Resolver::NO_RESULT:
      return noResult($keyProvided);
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $keyInHand = Resolver::resolveHandContents($keyResolver->result());
      if (!$keyInHand)
        return "Your " . $keyResolution->getName() . " is empty.";
      else
        $keyResolution = $keyInHand;
  }
  if (!$resolution->hasComponent('Lockable'))
    return "You cannot unlock " . $resolution->getName() . ".";
  GameState::getInstance()->incrementMoves();
  return $resolution->getComponent('Lockable')->unlock($keyResolution);
});

/* Openable */

//object.open();
Router::route('/^\s*([\w$_]+[\w\d$_]*)\s*\.\s*open\s*\(\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  if ($resolver->result() == Resolver::NO_RESULT)
    return noResult($provided);
  //resolve item if leftHand or rightHand is the source
  else switch ($resolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($resolver->result());
      if (!$itemInHand)
        return "Your " . $resolution->getName() . " is empty.";
      else
        $resolution = $itemInHand;
  }
  if (!$resolution->hasComponent('Openable'))
    return "You cannot open " . $target->getName() . ".";
  GameState::getInstance()->incrementMoves();
  return $resolution->getComponent('Openable')->open();
});

//object.close();
Router::route('/^\s*([\w$_]+[\w\d$_]*)\s*\.\s*close\s*\(\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  if ($resolver->result() == Resolver::NO_RESULT)
    return noResult($provided);
  //resolve item if leftHand or rightHand is the source
  else switch ($resolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($resolver->result());
      if (!$itemInHand)
        return "Your " . $resolution->getName() . " is empty.";
      else
        $resolution = $itemInHand;
  }
  if (!$resolution->hasComponent('Openable'))
    return "You cannot close " . $resolution->getName() . ".";
  GameState::getInstance()->incrementMoves();
  return $resolution->getComponent('Openable')->close();
});

//object.wind();
Router::route('/^\s*([\w$_]+[\w\d$_]*)\s*\.\s*wind\s*\(\s*\)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
  $resolver = Resolver::what($provided, Resolver::ANY, false);
  $resolution = $resolver->resolve();
  if ($resolver->result() == Resolver::NO_RESULT)
    return noResult($provided);
  //resolve item if leftHand or rightHand is the source
  else switch ($resolver->result()) {
    case Resolver::PLAYER_LEFT_HAND:
    case Resolver::PLAYER_RIGHT_HAND:
      $itemInHand = Resolver::resolveHandContents($resolver->result());
      if (!$itemInHand)
        return "Your " . $resolution->getName() . " is empty.";
      else
        $resolution = $itemInHand;
  }
  if (!$resolution->hasComponent('Windable'))
    return "You cannot wind " . $resolution->getName() . ".";
  GameState::getInstance()->incrementMoves();
  return $resolution->getComponent('Windable')->wind() . "  " . GameState::getInstance()->inspectRoom();
});

Router::route('/^\s*tablet\s*.\s*([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s*\((.*)\)\s*;$/', function ($command, $code, $pattern, $matches) {
  $methodName = $matches[1];
  $parameters = array();
  // $output = "player trying to run a method call with " . var_export($matches, true) . "\n";
  $compiler = new TabletCompilerService();
  $room = GameState::getInstance()->getPlayerRoom();
  $output = "";
  try {
    if ($room->hasComponent("Puzzle")) {
      $compiler->compile($room->getComponent("Puzzle")->getHeaderCode(), $code);
    }
    else {
      $compiler->compile("", $code);
    }
  } catch (\JavaException $e) {
    return "There was a problem compiling your Java tablet code.\n" . $e->getCause()->toString();
  }
  $cls = $compiler->getClass();
  $instance = $compiler->getInstance();
  try {
    $output = $compiler->invoke($methodName, $parameters);
  }
  catch (\JavaException $e) {
    $consoleOutput = $compiler->getConsoleOutput();
    if ($consoleOutput) $consoleOutput .= "\n";
    if (!java_instanceof($e->getCause(), java("edu.radford.rowdyred.puzzles.CharacterDeadException")))
      return $consoleOutput
        . "There was a problem executing your Java tablet code.\n"
        . java_values($e->getCause()->toString());
  }
  if ($room->hasComponent("Puzzle")) {
    GameState::getInstance()->incrementMoves();
    $output = $room->getComponent("Puzzle")->solve($instance) . "  " . $output;
  }
  $consoleOutput = $compiler->getConsoleOutput();
  if ($consoleOutput) $consoleOutput .= "\n";
  $output =  $consoleOutput . $output;
  if (!$output) $output = "Your code executed, but did not return a value or print to the standard output stream.  There are no puzzles in the room that need to be solved.";
  if (GameState::getInstance()->getPlayerRoom()->getName() != $room->getName())
    $output .= "\n" . GameState::getInstance()->inspectRoom();
  $compiler->clean();
  return $output;
});
