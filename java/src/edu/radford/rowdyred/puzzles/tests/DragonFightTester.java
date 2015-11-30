package edu.radford.rowdyred.puzzles.tests;

import edu.radford.rowdyred.puzzles.*;

public class DragonFightTester {

  public static void main(String[] args) {
    Dragon dragon = new Dragon("dragon", 1000);
    Player me = new Player("player", 100);
    Salve salve = new Salve();
    Weapon sword = new Weapon("sword", 75, 0, me);
    Shield shield = new Shield(0.91, 0.80);
    Weapon crossbow = new Weapon("crossbow", 40, 35, me);

    sword.setWielder(me);
    crossbow.setWielder(me);

    while (dragon.isAlive()) {
      if (dragon.isInhaling()) {
//        System.out.println("blocking!");
        me.block(shield);
      }
      if (me.getHealthPoints() <= 30) {
        me.heal(salve);
      }
      else if (dragon.isFlying()) {
//        System.out.println("crossbow!");
        me.attack(dragon, crossbow);
      }
      else {
//        System.out.println("sword!");
        me.attack(dragon, sword); 
      }
    }

System.out.println(salve.getUsesLeft());


  }



}

