package edu.radford.rowdyred.puzzles;

public class Player implements Healable {
	
	private static final int MAX_HEALTH = 100;
	private int healthPoints = MAX_HEALTH;
	
	public void doNothing() {
	
	}
	
	public void sleep(Bed bed) {
		
	}

	@Override
	public void heal(Healer healer) {
		healthPoints += healer.getHealthPoints();
		if (healthPoints > MAX_HEALTH)
			healthPoints = MAX_HEALTH;		
	}
	
	public int getHealthPoints() {
		return healthPoints;
	}
	
}
