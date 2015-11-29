package edu.radford.rowdyred.puzzles;

public class Enemy implements Healable {

	private static final int MAX_HEALTH = 10;
	protected int healthPoints;
	
	public Enemy() {
		super();
		healthPoints = MAX_HEALTH;
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
