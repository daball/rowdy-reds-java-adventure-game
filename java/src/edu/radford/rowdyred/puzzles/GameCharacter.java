package edu.radford.rowdyred.puzzles;

public class GameCharacter implements Healable, Attackable {

  protected int initHealth, currentHealth;
  protected boolean meleeEngageable, engaged;
  protected String name;
  protected Shield shield;
  protected GameCharacter adversary;

  public GameCharacter(String name, int initialHealth) {
    super();
    this.name = name;
    this.initHealth = initialHealth;
    this.currentHealth = initialHealth;
    this.meleeEngageable = true;
  }

  private String s(int value) {
    if (value == 1)
      return "";
    return "s";
  }
  
  @Override
  public void heal(HealingItem healingItem) {
    if (!isAlive())
      throw new CharacterDeadException();
    shield = null;

    currentHealth += healingItem.getHealthPoints();
    if (currentHealth > initHealth)
      currentHealth = initHealth;
    int uses = healingItem.getUsesLeft();
    System.out.println(name + " healed and now has " + currentHealth + " HP." + " Salve has " 
      + uses + " heal" + s(uses) + " left.");
    if (adversary != null)
      adversary.takeYourTurnStupid(this);

  }

  public void attack(GameCharacter target, Weapon weapon) {
    if (!isAlive())
      throw new CharacterDeadException();
    shield = null;
    adversary = target;
    this.engage();
    System.out.println(name + " attacks " + adversary.getName() + " with " + weapon.getName() + "!");
    target.receiveDamage(weapon, this);
    adversary.takeYourTurnStupid(this);

  }

  public void block(Shield shield) {
    if (!isAlive())
      throw new CharacterDeadException();
    this.shield = shield;
    System.out.println(name + " blocks with shield!");
    //      turnSpent = true;
    if (adversary != null)
      adversary.takeYourTurnStupid(this);
  }

  public void engage() {
//    if (!isAlive())
//      throw new CharacterDeadException();
    if (!engaged) {
      engaged = true;
      System.out.println(name + " engages in battle!");
    }
  }

  public boolean isAlive() {
    return currentHealth > 0;
  }

  public boolean isMeleeEngageable() {
    return meleeEngageable;
  }

  @Override
  public void receiveDamage(Weapon weapon, GameCharacter attacker) {    
    int damage = weapon.dealDamage(this);
    if (shield != null) {
      damage = shield.absorbDamage(damage, this.isMeleeEngageable() && attacker.isMeleeEngageable());
      shield = null;
    }
    currentHealth -= damage;
    this.engage();
    if (damage == 0)
      System.out.println(weapon.getWielder().getName() + " missed the " + name + "!");
    else
      System.out.println(name + " took " + damage + " points of damage and now has " + currentHealth + " HP.");    
  }

  public int getHealthPoints() {
    return currentHealth;
  }
  
  public String getName(){
    return name;
  }

  public void takeYourTurnStupid(GameCharacter sender) {

  }

}
