using System;
using experimentalmonoikvmcompileandrun.Models;

namespace experimentalmonoikvmcompileandrun
{
  public interface IUnlockable
  {
    string unlock(Key key);
  }
}

