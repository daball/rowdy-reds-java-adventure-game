<?php

namespace engine;

class Router {
  private static $routes = array();
  public static function init() {
    self::$routes = array();
  }

  public static function route($patterns, $route) {
    if (!isset(self::$routes))
      self::init();
    if (!is_array($patterns))
      $patterns = array($patterns);
    foreach ($patterns as $pattern)
      self::$routes[$pattern] = $route;
  }

  public static function dispatch($command, $code='') {
    foreach (self::$routes as $pattern => $route) {
      $matches = array();
      if (preg_match($pattern, $command, $matches)) {
        $trim = function ($str) { return trim($str); };
        $matches = array_map($trim, $matches);
        return $route($command, $code, $pattern, $matches);
      }
    }
    return "";
  }
}
