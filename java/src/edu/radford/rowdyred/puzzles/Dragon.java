package edu.radford.rowdyred.puzzles;

public class Dragon extends Enemy {

	private static final int MAX_HEALTH = 1000;
	private boolean flying, inhaling;
	
	public Dragon() {
		super();
		healthPoints = MAX_HEALTH;
		flying = false;
		inhaling = false;
	}
	
	public boolean isFlying() {
		return flying;
	}
	
	public boolean isInhaling() {
		return inhaling;
	}
	
/*	public String nextTurn() {
		StringBuilder sb = new StringBuilder();
		if (!isFlying && !isInhaling) {
			sb.append("The dragon takes to the air!\n");
		}
		else if (isInhaling) {			
			sb.append("The dragon starts inhaling!\n");
		}
		else if ()
		sb.append("The dragon lands on the ground!\n");
		return sb.toString();
	}
*/
	
	public void Attack (Player player) {
		
	}
	
}
