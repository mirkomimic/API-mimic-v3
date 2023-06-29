<?php

namespace App\Http;

class Route
{
  public static function get(string $routename, array $callback, array $args)
  {
    $class = $callback[0];
    // var_dump($class);
    $method = $callback[1];
    // var_dump($method);
    if ((new self)->get_uri() == $routename) {
      // var_dump((new self)->get_uri());
      // var_dump($routename);
      // var_dump($_GET);
      // var_dump(Url::getParams());
      if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET[$routename])) {

        if (!empty($args))
          return call_user_func_array([new $class, $method], $args);
        else
          return call_user_func([new $class, $method]);
      }
    }
  }

  public static function post(string $routename, array $callback, array $args = null)
  {
    $class = $callback[0];
    $method = $callback[1];
    if ((new self)->get_uri() == $routename) {
      if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_GET[$routename])) {

        if (!empty($args))
          return call_user_func_array([new $class, $method], $args);
        else
          return call_user_func([new $class, $method]);
      }
    }
  }

  public static function delete(string $routename, array $callback, array $args)
  {
    $class = $callback[0];
    $method = $callback[1];
    if ((new self)->get_uri() == $routename) {
      if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

        if (!empty($args))
          return call_user_func_array([new $class, $method], $args);
        else
          return call_user_func([new $class, $method]);
      }
    }
  }


  public function get_uri()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $exp = explode("/", $uri);
    $uri = $exp[3];
    // var_dump($uri . '/' . $exp[4]);

    // $uri = (strpos($uri, "?")) ? substr($uri, 0, strpos($uri, "?")) : $uri;
    // $params = Url::getParams();
    // var_dump($params);
    // var_dump($uri . '/' . $params);
    return $uri . '/' . $exp[4];
    // home, about...
    // return $uri; 
  }
}
