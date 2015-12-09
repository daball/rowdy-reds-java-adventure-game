package edu.radford.rowdyred.puzzles;

import java.util.Random;

public class Bed {
  private static Random rnd = new Random(); 
  private boolean sleptIn;
  private int sleepNumber;

  public Bed() {
    super();
    this.sleptIn = false;
    generateSleepNumber();
  }
  
  public void generateSleepNumber() {
    sleepNumber = rnd.nextInt(100) + 1;
  }
  
  public int getSleepNumber() {
    return sleepNumber;
  }
	
	public boolean isSleptIn() {
	  return sleptIn;
	}

}
