<?php

namespace App\Http;

class Url
{
  public static function getParams()
  {
    // https://www.geeksforgeeks.org/how-to-get-parameters-from-a-url-string-in-php/

    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_components = parse_url($url);
    parse_str($url_components["query"] ?? null, $params);

    return $params;
  }

  public static function getParamsString()
  {
    $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_components = parse_url($url);

    if (isset($url_components["query"]))
      return '?' . $url_components["query"];
    else
      return null;
  }
}
