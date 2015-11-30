package edu.radford.rowdyred.puzzles;

public class Dragon extends Enemy {

	private boolean flying, inhaling;
	private String[] attackPattern ={ "fire","tail","fly","fire","land" };
	private int turnNumber;
	private Weapon fire, tail;
	
	public Dragon(String name, int initialHealth) {
		super(name, initialHealth);
		flying = false;
		inhaling = false;
		turnNumber = 0;
		fire = new Weapon("fireball", 100, 75, this);
		tail = new Weapon("tail whip", 25, 0, this);
	}
	
	public boolean isFlying() {
		return flying;
	}
	
	public boolean isInhaling() {
		return inhaling;
	}
	
	public void nextTurn(GameCharacter target) {
	  if (!engaged) {
	    System.out.println("The dragon doesn't seem to notice you.");
	    return;
	  }
	  if (!isAlive()) {
	    System.out.println("The dragon is dead! Victory is yours!");
	    return;
	  }

	  if (inhaling) {
	    inhaling=false;
	    attack(target, fire);
	  }
	  
	  switch (attackPattern[turnNumber]) {
	  case "fire":
	    inhaling = true;	 
	    System.out.println("The dragon inhales!");
	    break;
	  case "tail":
	    attack(target, tail);
      break;      
	  case "fly":
	    flying = true;
	    meleeEngageable = false;
	    System.out.println("The dragon takes flight!");
      break;
    case "land":
      flying = false;
      meleeEngageable = true;
      System.out.println("The dragon lands back on the ground!");
      break;
	  }    
	  
//	  this.resetTurn();
//	  target.resetTurn();
		turnNumber++;
		if (turnNumber > attackPattern.length -1)
		  turnNumber = 0;

	}
	
/*	@Override
	public void receiveDamage(Weapon weapon, GameCharacter attacker) {
	  super.receiveDamage(weapon, attacker);
	  nextTurn(weapon.getWielder());  
	}
	*/
	@Override
	public void takeYourTurnStupid(GameCharacter sender) {
	  if (isAlive())
	    nextTurn(sender);
	  else {
	    System.out.println("The " + name + " is defeated!  Victory is yours!");
	    System.out.println(adversary.getHealthPoints());
	  }
	}
	
}
