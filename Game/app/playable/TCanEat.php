<?php

namespace playable;

trait TCanEat {
  private $hungry = true;
  private $feedCallback = null;

  public function isHungry()
  {
    return $this->hungry;
  }

  public function eat(Food $food)
  {
    $cb = $this->feedCallback;
    if ($cb)
      return $cb();
    return "The TCanEat eats food.";
  }

  public function onEat($fn)
  {
    $this->feedCallback = $fn;
  }
}
