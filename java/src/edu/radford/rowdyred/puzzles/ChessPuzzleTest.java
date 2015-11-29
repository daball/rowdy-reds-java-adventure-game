package edu.radford.rowdyred.puzzles;

import static org.junit.Assert.*;

import org.junit.Test;

public class ChessPuzzleTest {

  @Test
  public void testMyI() {
    ChessPuzzle p = new ChessPuzzle();
    assertEquals(0, p.myI());
  }

}
