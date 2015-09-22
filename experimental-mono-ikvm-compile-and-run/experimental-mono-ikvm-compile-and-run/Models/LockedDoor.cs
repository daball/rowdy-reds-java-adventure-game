using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Mvc.Ajax;

namespace experimentalmonoikvmcompileandrun.Models
{
  public class LockedDoor: Door, IUnlockable
  {
    private string password = "";
    private bool locked = true;

    public LockedDoor(string keyPassword) {
      this.password = keyPassword;
    }
    public LockedDoor() {}

    public new string open() {
      if (this.locked)
        return "This door is locked.";
      else
        return base.open();
    }

    public string unlock(Key key) {
      if (this.locked) {
        if (key.ToString() == password) {
          return "You have unlocked the door.";
        }
        else {
          return "This key doesn't match this door. The door remains locked.";
        }
      }
      else {
        return "This door is already unlocked.";
      }
    }
  }
}

