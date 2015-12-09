<?php

namespace commands;
use engine\CommandProcessor;
use engine\GameState;

require_once __DIR__.'/../engine/GameState.php';
require_once __DIR__.'/../engine/CommandProcessor.php';
require_once 'BaseCommandHandler.php';

class AutoSuggest extends BaseCommandHandler
{

  private $verbs;

  public function __construct() {
    $this->verbs = array(
      'open',
      'close',
      'unlock',
      'lock',
      'assign',
      '=',
      'unset',
      'set',
      'move',
      'navigate',
      'equip',
    );
  }

  public function validateCommand($commandLine, $tabletCode)
  {
    foreach ($this->verbs as $searchVerb) {
      if (stristr($commandLine, $searchVerb) !== FALSE)
        return true;
    }
    return false;
  }

  public function executeCommand($commandLine, $tabletCode)
  {
    $verb = '';
    foreach ($this->verbs as $searchVerb) {
      if (stristr($commandLine, $searchVerb) !== FALSE) {
        $verb = $searchVerb;
        break;
      }
    }
    switch ($verb) {
      case 'open':
        return "In order to open something, you might try item.open();";
      case 'close':
        return "In order to close something, you might try item.close();";
      case 'lock':
        return "In order to lock something, you might try item.lock(key);";
      case 'unlock':
        return "In order to unlock something, you might try item.unlock(key);";
      case 'assign':
      case 'set':
      case 'unset':
      case '=':
        return "In order to assign something, you might try target = item; For example, me.leftHand = item; or anyContainer = anyItem;";
      case 'move':
      case 'navigate':
        return "In order to navigate, you might try the commands up, west, north, east, south, down.";
      case 'equip':
        return "In order to equip an item, you might try equip(item); or me.equip(item);";
    }
    return "I don't understand.";
  }
}
