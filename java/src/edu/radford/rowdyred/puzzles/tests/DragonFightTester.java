package edu.radford.rowdyred.puzzles.tests;

import edu.radford.rowdyred.puzzles.*;

public class DragonFightTester {

  public static void main(String[] args) {
    Dragon dragon = new Dragon(1000);
    Player player = new Player(100);
    Salve salve = new Salve();
    Weapon sword = new Weapon(75, true);
    Shield shield = new Shield(55, 90);
    Weapon crossbow = new Weapon(35, false);
    
    dragon.engage();
    for (int i = 1; i <= 10; i++)
    System.out.println(dragon.nextTurn());
    
  }
}
