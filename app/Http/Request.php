<?php

namespace App\Http;

use stdClass;

class Request
{
  public static function getArray(): stdClass
  {
    $request = file_get_contents('php://input');
    $request = json_decode($request);

    return $request;
  }
}
