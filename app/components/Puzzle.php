<?php

namespace components;

require_once 'BaseComponent.php';

class Puzzle extends BaseComponent {
  public function __construct() {
    $this->define(function ($puzzle) {
      $puzzle->setHeaderCode(function ($puzzle) {
        return "";
      });
      $puzzle->setIsSolved(function ($puzzle, $javaTabletInstance) {
        return false;
      });
    });
  }

  public function getHeaderCode() {
    return $this->trigger("headerCode", array($this));
  }

  public function setHeaderCode($closure) {
    return $this->on("headerCode", $closure);
  }

  public function isSolved($javaTabletInstance) {
    return $this->trigger("solved", array($this, $javaTabletInstance));
  }

  public function setIsSolved($closure) {
    return $this->on("solved", $closure);
  }
}
