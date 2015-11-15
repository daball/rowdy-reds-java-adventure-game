<?php

namespace playable;

trait TCreateWithName
{
  public $name = "";

  public static function create($name)
  {
    return new self($name);
  }
}
