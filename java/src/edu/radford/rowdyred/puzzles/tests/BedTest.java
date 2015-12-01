package edu.radford.rowdyred.puzzles.tests;

import static org.junit.Assert.*;
import org.junit.Test;
import edu.radford.rowdyred.puzzles.*;

public class BedTest {

  public static Bed bed1, bed2, bed3;
  public Player me;
  
//  @Test
//  public void testBed() {
  public static void main(String[] args) {  
    bed1 = new Bed();
    bed2 = new Bed();
    bed3 = new Bed();
    Bed justright;
//    me = new Player();
    checkBeds();
    
    if ((bed1.getSleepNumber() > bed2.getSleepNumber() && bed2.getSleepNumber() > bed3.getSleepNumber()) ||
        (bed1.getSleepNumber() < bed2.getSleepNumber() && bed2.getSleepNumber() < bed3.getSleepNumber())) { 
      justright = bed2;
      System.out.println("found 2");
    }
    else if ((bed2.getSleepNumber() > bed1.getSleepNumber() && bed2.getSleepNumber() > bed3.getSleepNumber()) ||
        (bed2.getSleepNumber() < bed1.getSleepNumber() && bed2.getSleepNumber() < bed3.getSleepNumber())) {
      System.out.println("found 1");
      justright = bed1;
    }
    else {
      System.out.println("must be 3");
      justright = bed3;
    }
    
    System.out.println(bed1.getSleepNumber());
    System.out.println(bed2.getSleepNumber());
    System.out.println(bed3.getSleepNumber() + "\n");
    System.out.println(justright.getSleepNumber());
    
  }
  
  public static void checkBeds() {
    while (bed1.getSleepNumber() == bed2.getSleepNumber() || bed1.getSleepNumber() == bed3.getSleepNumber() || bed2.getSleepNumber() == bed3.getSleepNumber()) {
      bed2.generateSleepNumber();
      bed3.generateSleepNumber();
    }
    
  }

}
