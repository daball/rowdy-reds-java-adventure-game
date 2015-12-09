<?php

namespace components;

require_once 'BaseComponent.php';

class Puzzle extends BaseComponent {
  public function __construct() {
    $this->define(function ($puzzle) {
      $puzzle->setHeaderCode(function ($puzzle) {
        return "";
      });
      $puzzle->onBeforeSolve(function ($puzzle, $javaTabletInstance) {
        return false;
      });
      $puzzle->onSolve(function ($puzzle, $javaTabletInstance) {
        return "You have solved the puzzle in the room.";
      });
      $puzzle->onRefuseSolve(function ($puzzle, $javaTabletInstance) {
        return "You ran some Java code but it didn't seem to have any effect.";
      });
    });
  }

  public function getHeaderCode() {
    return $this->trigger("headerCode", array($this));
  }

  public function setHeaderCode($closure) {
    return $this->on("headerCode", $closure);
  }

  public function solve($javaTabletInstance) {
    if ($this->trigger("beforeSolve", array($this, $javaTabletInstance)))
      return $this->trigger("solve",  array($this, $javaTabletInstance));
    else
      return $this->trigger("refuseSolve",  array($this, $javaTabletInstance));
  }

  public function onBeforeSolve($closure) {
    return $this->on("beforeSolve", $closure);
  }

  public function onSolve($closure) {
    return $this->on("solve", $closure);
  }

  public function onRefuseSolve($closure) {
    return $this->on("refuseSolve", $closure);
  }
}
