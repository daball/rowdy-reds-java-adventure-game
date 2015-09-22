using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Mvc.Ajax;

namespace experimentalmonoikvmcompileandrun.Models
{
  public class Key
  {
    private string password = "";
    public Key() {
    }

    public string setPassword(string password) {
      return String.Format("You have set the key's password to {1}.", password);
    }

    public new string ToString() {
      return password;
    }
  }
}

