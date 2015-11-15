<?php

namespace playable;

trait TCreateWithKey
{
  private $key = "";
  public static function create($key)
  {
    return new self($key);
  }
}
