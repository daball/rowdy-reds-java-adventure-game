package edu.radford.rowdyred.puzzles;

public class Shield {

  protected double meleeProtection, projectileProtection;
  
  public Shield(double meleeProtection, double projectileProtection) {
    if (meleeProtection > 1) meleeProtection = 0.99;
    if (meleeProtection < 0) meleeProtection = 0.0;
    if (projectileProtection > 1) projectileProtection = 0.99;
    if (projectileProtection < 0) projectileProtection = 0.0;
    this.meleeProtection = meleeProtection;
    this.projectileProtection = projectileProtection;
  }
  
  public int absorbDamage(int damagePoints, boolean melee) {
    double damage = damagePoints;
    if (melee) { 
      damage = damage * (1 - meleeProtection);
    }
    else {
      damage = damage * (1 - projectileProtection);
    }
    damagePoints = (int) damage;
    return damagePoints;
  }
}
