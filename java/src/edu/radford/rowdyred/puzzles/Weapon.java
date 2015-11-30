package edu.radford.rowdyred.puzzles;

public class Weapon {

  protected int meleeDamage, projectileDamage;
  protected GameCharacter wielder;
  protected String name;
  
  public Weapon(String name, int meleeDamage, int projectileDamage, GameCharacter wielder) {
    super();
    this.name = name;
    this.meleeDamage = meleeDamage;
    this.projectileDamage = projectileDamage;
    this.wielder = wielder;
  }
  
  public int dealDamage(GameCharacter gameCharacter) {
    if (wielder == null)
      return 0;
    if(gameCharacter.isMeleeEngageable() && wielder.isMeleeEngageable()) 
      return meleeDamage;
    else
      return projectileDamage;
  }
  
  public void setWielder(GameCharacter wielder) {
    this.wielder = wielder;
  }
  
  public String getName() {
    return name;
  }
  
  public GameCharacter getWielder() {
    return wielder;
  }
  
}
