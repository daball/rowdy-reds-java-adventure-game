package edu.radford.rowdyred.puzzles;

public class Tablet {

	Player me;
	
	public Tablet(Player me) {
		this.me = me;
	}

	public void checkBed(Bed bed) {
		if (bed.isTooHard()) 
			me.doNothing();
		else if (bed.isTooSoft())
			me.doNothing();
		else
			me.sleep(bed);
	}
	
	public void fight(Dragon dragon) {
		
	}
	
}
