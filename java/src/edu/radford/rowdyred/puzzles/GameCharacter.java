package edu.radford.rowdyred.puzzles;

public class GameCharacter implements Healable {
  
  protected int initHealth, currentHealth;
  
  public GameCharacter(int initialHealth) {
    super();
    this.initHealth = initialHealth;
    this.currentHealth = initialHealth;
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
}
