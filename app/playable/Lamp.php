<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Assignable.php';
require_once __DIR__.'/../components/Windable.php';

use \game\GameObject;
use \components\Assignable;
use \components\Windable;

/**
 * A Lamp item is used to light dark areas.
 */
class Lamp extends GameObject
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->define(function ($lamp) {
      $inspector = $lamp->getComponent('Inspector');
      $inspector->popEventHandler('inspect');
      $inspector->onInspect(function ($inspector) {
        return "You found a lamp.  A lamp can light your way through dark places.";
      });
      $lamp->addComponent(new Assignable());
      $lamp->addComponent((new Windable())->define(function ($windable) use ($lamp) {
        $windable->onWind(function ($windable) {
          $windable->publish('Lamp', "wind");
          return "You wind the " . $lamp->getName() . " and it now shines brightly and lights up the darkness.";
        });
        $windable->onUnwind(function ($windable) {
          $windable->publish('Lamp', "unwind");
          return "You unwind the " . $lamp->getName() . " and it no longer lights up the darkness.";
        });
        $lamp->subscribe('Player', function ($sender, $queue, $message) use ($windable, $lamp) {
          if ($windable->isWound()) {
            if (is_array($message) && array_key_exists('action', $message)) {
              if ($message['action'] == 'setLocation') {
                $windable->unwind();
                $windable->publish('System.out', array('prepend' => "The " . $lamp->getName() . " went out."));
              }
            }
          }
        });
      }));
    });
  }
}
