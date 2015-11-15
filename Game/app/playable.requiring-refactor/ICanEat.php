<?php

namespace playable;

interface ICanEat {
  public function isHungry();
  public function eat(Food $food);
  public function onEat($fn);
}
