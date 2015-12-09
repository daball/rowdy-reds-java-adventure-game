package edu.radford.rowdyred.game;

public class Player extends GameObject {
  private Hand leftHand;
  public Hand getLeftHand() {
    return leftHand;
  }
  
  private Hand rightHand;
  public Hand getRightHand() {
    return rightHand;
  }
  
  public String inspect()
  {
    return "Howdy, I'm Rowdy Red!";
  }
  public String inspect(String gameObject)
  {
    return "{\"equip\": \"" + gameObject + "\"}";
  }
  public String equip(String item)
  {
    return "{\"equip\": \"" + item + "\"}";
  }
}
