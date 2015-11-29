<?php

namespace commands;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/Router.php';
require_once __DIR__.'/../util/Resolver.php';
require_once __DIR__.'/../java/TabletCompilerService.php';

use \engine\GameState;
use \engine\Router;
use \util\Resolver;
use \TabletCompilerService;

//reusable no matching result output
function noResult($provided) {
  $provided = insertAOrAn($provided);
  return "I don't know what $provided is.";
}

/* Assignable */

//assign: target = source;
Router::route('/^\s*([\w\d$_.]+)\s*=\s*([\w\d$_.]+)\s*;\s*$/', function ($command, $code, $pattern, $matches) {
  $target = $matches[1];
  $source = $matches[2];
  $index = -1; //index in container to assign, this is variable for backpack

  $sourceResolver = Resolver::what($source);
  if ($sourceResolver->result() == Resolver::NO_RESULT)
    return noResult($source);
  $source = $sourceResolver->resolve();

  $targetResolver = Resolver::what($target);
  if ($targetResolver->result() == Resolver::NO_RESULT)
    return noResult($target);
  $target = $targetResolver->resolve();

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

  //backpack index condition
  if ($targetResolver->result() == Resolver::PLAYER_BACKPACK_INDEX)
    $index = $matches[2];

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
    return "$item is not an equippable item.";

  //perform equip
  return GameState::getInstance()->getPlayer()->equipItem($item);
});

/* Inspector */

//inspect|look object || [me.]inspect(object);
Router::route(array(
    '/^\s*(?:(?:inspect)|(?:look))(.*)$/i',
    '/^(?:me\s*.\s*){0,1}\s*(?:inspect\s*\(\s*)([\w$_]+[\w\d$_]*)(?:\s*\)\s*;\s*)$/',
    '/^\s*System\s*.\s*out\s*.\s*print(?:ln){0,1}\s*\(\s*([\w$_]+[\w\d$_]*)\s*\)\s*;\s*$/',
  ), function ($command, $code, $pattern, $matches) {
  $provided = $matches[1];
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
      return "The $provided is in your $where.  " . $inspector->inspect();
    case Resolver::PLAYER_EQUIPMENT_ITEM:
      return "You have equipped the $provided.  " . $player->getEquipmentItem($inspectWhat)->getComponent('Inspector')->inspect();
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
        return "Your " . $resolution->getName() . " is empty.";
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
        return "Your " . $resolution->getName() . " is empty.";
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
        return "Your " . $resolution->getName() . " is empty.";
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
        return "Your " . $resolution->getName() . " is empty.";
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
  return $resolution->getComponent('Windable')->wind() + "  " + GameState::getInstance()->inspectRoom();
});

Router::route('/^\s*tablet\s*.\s*([A-Za-z$_]{1}[A-Za-z0-9$_]*)\s*\((.*)\)\s*;$/', function ($command, $code, $pattern, $matches) {
  $output = "player trying to run a method call with " . var_export($matches, true) . "\n";
  $compiler = new TabletCompilerService();
  $cls = $compiler->compile($code);
  $output = $compiler->invoke($matches[1], array());
  return $output;
});
