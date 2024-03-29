package edu.radford.rowdyred.puzzles.tests;

import edu.radford.rowdyred.puzzles.*;

public class DragonFightTester {

  public static void main(String[] args) {
    Dragon dragon = new Dragon("Dragon", 1000);
    Player me = new Player("Rowdy Red", 100);
    Salve salve = new Salve(7, 50);
    Weapon sword = new Weapon("sword", 75, 0, me);
    Weapon magicSword = sword;
    Shield shield = new Shield(0.91, 0.80);
    Weapon crossbow = new Weapon("crossbow", 40, 35, me);

    while (dragon.isAlive()) {
      if (me.getHealthPoints() <= 30) {
        me.heal(salve);
      }
      else if (dragon.isInhaling()) {
        me.block(shield);
      }
      else if (dragon.isFlying()) {
        me.attack(dragon, crossbow);
      }
      else {
        me.attack(dragon, magicSword);
      }
    }
  }
}

