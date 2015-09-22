using System;
using System.Collections.Generic;
using System.Linq;
using System.ServiceModel.Web;
using System.Web;
using System.Web.Http;
using ikvm;

namespace experimentalmonoikvmcompileandrun.Controllers
{
    public class AppController : ApiController
    {
        [WebGet(UriTemplate="app/start")]
        public IHttpActionResult StartSession()
        {
          return Ok("Session started.");
        }

        public IHttpActionResult ExecuteCode(String code) {
          return Ok (code);
        }
    }
}
