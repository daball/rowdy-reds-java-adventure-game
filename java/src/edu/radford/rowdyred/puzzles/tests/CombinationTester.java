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
  }

}
