package edu.radford.rowdyred.puzzles;

public class GameCharacter implements Healable { //, Attackable {
  
  protected int initHealth, currentHealth;
  protected boolean meleeEngageable;
  
  public GameCharacter(int initialHealth) {
    super();
    this.initHealth = initialHealth;
    this.currentHealth = initialHealth;
    this.meleeEngageable = true;
  }
  
  @Override
  public void heal(Healer healer) {
    currentHealth += healer.getHealthPoints();
    if (currentHealth > initHealth)
      currentHealth = initHealth;  
  }
  
  public int getHealthPoints() {
    return currentHealth;
  }

  public void attack(GameCharacter gameChar, Weapon weapon) {
    gameChar.dealDamage(weapon);
    
  }
  
  public void dealDamage(Weapon weapon) {
    currentHealth -= weapon.getDamage();
  }
  
  public boolean isAlive() {
    return currentHealth > 0;
  }
}
