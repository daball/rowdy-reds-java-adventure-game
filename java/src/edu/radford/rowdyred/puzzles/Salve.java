package edu.radford.rowdyred.puzzles;

public class Salve implements HealingItem {
  int uses;

  public Salve() {
		super();
		uses = 7;
	}
	
	public Salve(int uses) {
		super();
		this.uses = uses;
	}
	
	@Override
	public int getHealthPoints() {
		if (uses > 0) {
			uses--;
			return 50;
		}
		
		return 0;
	}

	@Override
	public int getUsesLeft() {
    return uses;
  }
	
}
