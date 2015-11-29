package edu.radford.rowdyred.puzzles;

public class Salve implements Healer {

	int uses;
	
	public Salve() {
		super();
		uses = 10;
	}
	
	public Salve(int uses) {
		super();
		this.uses = uses;
	}
	
	@Override
	public int getHealthPoints() {
		if (uses > 0) {
			uses--;
			return 75;
		}
		
		return 0;
	}

}
