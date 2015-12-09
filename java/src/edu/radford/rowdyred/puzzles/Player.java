package edu.radford.rowdyred.puzzles;

public class Player extends GameCharacter implements Healable {
		
	public Player() {
	super("Rowdy Red", 100);
	}
  
  public Player(String name, int initHealth) {
	  super(name, initHealth);
	}
	
	public void doNothing() {
	  if (adversary != null)
	    adversary.takeYourTurnStupid(this);
	}
	
	public void sleep(Bed bed) {
		
	}
	
}
