<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Container.php';

use \game\GameObject;
use \components\Container;
use \components\Inspector;

/**
 * A BasicContainer game object stores items. It does so by implementing a
 * Container component.
 **/
class BasicContainer extends GameObject
{
  public function __construct($name) {
    parent::__construct($name);
    $this->define(function ($basicContainer) {
      $container = new Container();
      // $inspector->onInspect(function ($inspector) {
      //   $openable = $inspector->getParent()->getComponent('Openable');
      //   if ($openable->isOpened())
      //     return "The container is open.";
      //   else
      //     return "The container is not open.";
      // });
      $basicContainer->addComponent($container);

      $inspector = new Inspector();
      $inspector->popEventHandler('inspect');
      $inspector->onInspect(function ($inspector) {
        $openable = $inspector->getParent()->getComponent('Openable');
        if ($openable->isOpened())
          return "The container is open.  You see inside ";
        else
          return "The container is not open.";
      });
      $basicContainer->addComponent($inspector);
    });
  }
}
