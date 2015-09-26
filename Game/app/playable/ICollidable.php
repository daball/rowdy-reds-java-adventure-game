<?php

namespace playable;

/**
 * ICollidable objects represent obstacles in the way of navigation.
 **/
interface ICollidable
{
  /**
   * Returns a boolean indicating if the Player can proceed past the obstacle.
   * @return boolean
   **/
  public function isInTheWay();

  public function explainCollision();
}
