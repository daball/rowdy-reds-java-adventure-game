<?php

namespace playable;

trait TCollidable
{
  private $explainCollisionCallback = null;

  /**
   * Returns a boolean indicating if the Player can proceed past the obstacle.
   * @return boolean
   * @ignore
   **/
  public function isInTheWay() {
    return $this->isClosed();
  }

  public function explainCollision($direction)
  {
    if ($this->explainCollisionCallback)
      return $this->explainCollisionCallback($direction);
    else
      return "There is something blocking you from going $direction. (Developer, please override this.)";
  }

  /**
   * @ignore
   **/
  public function setExplainCollision($fn) {
    $this->explainCollision = $fn;
    return $this;
  }
}
