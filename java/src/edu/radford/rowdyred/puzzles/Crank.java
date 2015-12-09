package edu.radford.rowdyred.puzzles;

public class Crank {

  private Handle handle;
  private Portcullis portcullis;
  private String key;
  
  public Crank(Portcullis portcullis, String key) {
    super();
    this.portcullis = portcullis;
    this.key = key;
  }
  
  public void setHandle(Handle handle) {
    this.handle = handle;
  }
  
  public void turn() {
    if (handle != null)
      portcullis.raise(this);
    else
      throw new NoHandleOnCrankException();
  }
  
  public boolean auth(String key) {
    return this.key.equals(key);
  }
}
