package edu.radford.rowdyred.puzzles;

import java.util.Random;

public class Combination {
  public static final int START_VALUE = new Random().nextInt(1000);
  private boolean isOpen = false;
  
  public boolean open(int c1, int c2, int c3) {
    int sol1 = START_VALUE, sol2, sol3;    
    sol1 += 143;
    sol2 = sol1 * 3 - 77;
    sol3 = sol1 * sol2;
    sol2 /= 2;
    sol2 -= sol1;
    sol3 = sol3 + sol1 + sol2;
    
    if (c1 == sol1 && c2 == sol2 && c3 == sol3)
      isOpen = true;
    
    return isOpen;
  }
  
  public boolean isOpen() {
    return isOpen;
  }
  
  public int getStart() {
    return START_VALUE;
  }
}
