<?php

require_once 'Assignable.php';

/**
 * Player represents the player's avatar throughout the game.
 * @author David Ball
 */
class Player
{
  /**
   * @ignore
   **/
  public static $gameState = null;

  /**
   * Player's left hand.
   * @var Assignable
   **/
  public $leftHand = null;

  /**
   * Player's right hand.
   * @var Assignable
   **/
  public $rightHand = null;

  /**
   * Player's location in the game.
   * @var Assignable
   * @ignore
   **/
  public $location = null;

  public static function init($gameState) {
    self::$gameState =  $gameState;
  }

  /**
    * Navigates North.
    * @return String
    **/
  public function navigateNorth() {
    return self::$gameState->navigate(Direction::$north);
  }

  /**
    * Navigates South.
    * @return String
    **/
  public function navigateSouth() {
    return self::$gameState->navigate(Direction::$south);
  }

  /**
    * Navigates East.
    * @return String
    **/
  public function navigateEast() {
    return self::$gameState->navigate(Direction::$east);
  }

  /**
    * Navigates West.
    * @return String
    **/
  public function navigateWest() {
    return self::$gameState->navigate(Direction::$west);
  }

  /**
   * @ignore
   **/
  public function serialize() {
    return serialize(
      array(
        'location' => $this->location,
        'leftHand' => $this->leftHand,
        'rightHand' => $this->rightHand,
      )
    );
  }

  /**
   * @ignore
   **/
  public function unserialize($data) {
    $data = unserialize($data);
    $this->location = $data['location'];
    $this->leftHand = $data['leftHand'];
    $this->rightHand = $data['rightHand'];
  }
}
