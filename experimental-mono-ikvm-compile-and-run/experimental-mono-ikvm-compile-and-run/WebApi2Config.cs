using System.Web.Http;

public static class WebApi2Config
{
  public static void Setup(HttpConfiguration config)
  {
    config.MapHttpAttributeRoutes();
  }
}