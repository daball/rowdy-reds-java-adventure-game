using System;

namespace experimentalmonoikvmcompileandrun.Models
{
  public class Door: IOpenable
  {
    private bool isOpen = false;

    public Door() {
    }

    public string open() {
      if (isOpen) {
        return "The door is already opened.";
      }
      else {
        isOpen = true;
        return "You have opened the door.";
      }
    }

    public string close() {
      if (isOpen) {
        isOpen = false;
        return "You have closed the door.";
      }
      else {
        return "The door is already closed.";
      }
    }

    public new string ToString() {
      return "There is a door blocking the way";
    }
  }
}

