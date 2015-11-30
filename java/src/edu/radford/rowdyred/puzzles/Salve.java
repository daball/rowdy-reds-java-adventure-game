package edu.radford.rowdyred.puzzles;

public class Salve implements HealingItem {
  int uses, healingPoints;

  public Salve(int uses, int healingPoints) {
		super();
		this.uses = uses;
		this.healingPoints = healingPoints;
	}
	
	public Salve(int uses) {
		super();
		this.uses = uses;
	}
	
	@Override
	public int getHealthPoints() {
		if (uses > 0) {
			uses--;
			return healingPoints;
		}
		return 0;
	}

	@Override
	public int getUsesLeft() {
    return uses;
  }
}
