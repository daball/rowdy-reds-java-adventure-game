package edu.radford.rowdyred.puzzles.tests;

import static org.junit.Assert.*;

import org.junit.Test;

import edu.radford.rowdyred.puzzles.Combination;

public class CombinationTester {

  @Test
  public void test() {
    Combination safe = new Combination();
    int c1 = safe.getStart(), c2 = 0, c3 = 0;
    
    c1 = c1 + 143;
    assertFalse(safe.open(c1, c2, c3));

    c2 = c1 * 3 - 77;
    assertFalse(safe.open(c1, c2, c3));
    
    c3 = c1 * c2;
    assertFalse(safe.open(c1, c2, c3));
    
    c2 = c2 / 2 - c1;
    assertFalse(safe.open(c1, c2, c3));

    c3 += c1 + c2;
    
    safe.open(c1, c2, c3);
    assertTrue(safe.isOpen());

//  I, the master of the house, have written down this confounded combination so I won't forget it!    
//  Take c1 and add 143 to it and put that answer into c1
//  Then, take c1, multiply it by three and subtract seventy-seven and put that answer in c2
//  Next, multiply c1 and c2 together and put that answer in c3.
//  Then, divide c2 in half and put that into c2
//  Next, subtract c1 from c2 and put that answer into c2
//  Finally, add all three combinations together, and put the answer in c3
//  Remember to open(c1, c2, c3); on the safe!
//Good thing I wrote this down, because no one will ever figure it out, except for me, since
//I'm the only one who has this note!  All this because I had combination 1 2 3 4 5 on my luggage!
 
  }

}
