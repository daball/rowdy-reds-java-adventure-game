<?php

namespace playable;

require_once __DIR__.'/../game/GameObject.php';
require_once __DIR__.'/../components/Inspector.php';
require_once __DIR__.'/../components/Openable.php';
require_once 'BasicContainer.php';

use \game\GameObject;
use \components\Inspector;
use \components\Openable;

class OpenableContainer extends BasicContainer
{
  public function __construct($name) {
    parent::__construct($name);
    $this->define(function ($basicContainer) {
      $openable = new Openable();
      $openable->onOpen(function ($openable) {
        return "The container swings open.";
      });
      $openable->onRefuseOpen(function ($openable) {
        return "The container does not open.";
      });
      $openable->onClose(function ($openable) {
        return "The container swings closed.";
      });
      $openable->onRefuseClose(function ($openable) {
        return "The container does not close.";
      });
      $basicContainer->addComponent($openable);

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
      $inspector->onInspect(function ($inspector) {
        $openable = $inspector->getParent()->getComponent('Openable');
        if ($openable->isOpened())
          return "The container is open.";
        else
          return "The container is not open.";
      });
      $basicContainer->addComponent($inspector);
    });
  }
}
