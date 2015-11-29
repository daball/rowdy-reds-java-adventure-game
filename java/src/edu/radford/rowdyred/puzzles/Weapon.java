package edu.radford.rowdyred.puzzles;

public class Weapon {

  protected int damage;
  protected boolean melee;
  
  public Weapon(int damage, boolean melee) {
    super();
    this.damage = damage;
    this.melee = melee;
  }
  
  public int getDamage() {
    return damage;
  }
  
  public boolean isMelee() {
    return melee;
  }
  
}
