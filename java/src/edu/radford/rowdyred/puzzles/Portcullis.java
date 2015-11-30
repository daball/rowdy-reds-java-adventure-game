package edu.radford.rowdyred.puzzles;

public class Portcullis {

private int numberOfTurns;
private String key;
  
  public Portcullis(String key) {
    numberOfTurns = 105;
    this.key = key;
  }
  
  public void raise(Crank crank) {
    if (crank != null && crank.auth(key))
      numberOfTurns--;  
  }
    
  public boolean isRaised() {
    return numberOfTurns <= 0;
  }
}
