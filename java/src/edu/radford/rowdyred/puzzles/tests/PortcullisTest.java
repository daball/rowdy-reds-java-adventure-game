package edu.radford.rowdyred.puzzles.tests;

import static org.junit.Assert.*;
import org.junit.Test;
import edu.radford.rowdyred.puzzles.*;

public class PortcullisTest {

  @Test
  public void testRaisePortcullis() {
    String key = "efsdg908hn3rv0tyobri7oirgfoli";
    Portcullis portcullis = new Portcullis(key);
    Crank crank = new Crank(portcullis, key);
    
    while (!portcullis.isRaised()) {
      crank.turn();
    }
    assertTrue(portcullis.isRaised());
  }
}
