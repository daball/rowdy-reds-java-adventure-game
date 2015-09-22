using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;
using System.Web.Http;
using RiaLibrary.Web;

namespace experimentalmonoikvmcompileandrun
{
	public class MvcApplication : System.Web.HttpApplication
	{
		public static void RegisterRoutes (RouteCollection routes)
		{
      routes.IgnoreRoute ("{resource}.axd/{*pathInfo}");

      routes.MapRoutes();

      routes.MapRoute (
        "Default",
        "{controller}/{action}/{id}",
        new { controller = "Home", action = "Index", id = "" }
      );

      routes.MapRoute (
        "app",
        "{controller}/{action}/{id}",
        new { controller = "App", action = "Index", id = "" }
      );

		}

		public static void RegisterGlobalFilters (GlobalFilterCollection filters)
		{
			filters.Add (new HandleErrorAttribute ());
		}

		protected void Application_Start ()
		{
			AreaRegistration.RegisterAllAreas ();
			RegisterGlobalFilters (GlobalFilters.Filters);
			RegisterRoutes (RouteTable.Routes);
		}
	}
}
