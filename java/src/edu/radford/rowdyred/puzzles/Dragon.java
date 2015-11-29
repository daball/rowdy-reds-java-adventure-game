package edu.radford.rowdyred.puzzles;

public class Dragon extends Enemy {

	private boolean flying, inhaling, engaged;
	private String[] attackPattern ={ "fire","tail","fly","fire","land" };
	private int turnNumber;
	
	public Dragon(int initialHealth) {
		super(initialHealth);
		flying = false;
		inhaling = false;
		turnNumber = 0;
	}
	
	public boolean isFlying() {
		return flying;
	}
	
	public boolean isInhaling() {
		return inhaling;
	}
	
	public void engage() {
	  engaged = true;
	}
	
	public String nextTurn() {
	  if (!engaged)
	    return "The dragon doesn't seem to notice you.";
	  String response = "";
	  switch (attackPattern[turnNumber]) {
	  case "fire":
	    inhaling = true;
	    response = "The dragon breathes fire at you!";
	    break;
	  case "tail":
	    response = "The dragon whips its tail at you!";
      break;
	  case "fly":
	    flying = true;
	    response = "The dragon takes flight!";
      break;
    case "land":
      flying = false;
      response = "The dragon lands back on the ground!";
      break;
	  }    
	  
		turnNumber++;
		if (turnNumber > attackPattern.length -1)
		  turnNumber = 0;
		return response;
	}

	
	public void Attack (Player player) {
		
	}
	
}
