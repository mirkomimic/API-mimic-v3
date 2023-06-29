<?php

namespace App\Controllers;

use App\Models\User;
use App\Http\Response;
use App\Http\Requests\RegisterRequest;

class UserController
{

  public static function store(RegisterRequest $request)
  {
    User::create([
      'firstname' => $request->data->firstname,
      'lastname' => $request->data->lastname,
      'phone' => $request->data->phone,
      'email' => $request->data->email,
    ]);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_message("User registered!");
    $response->send();
  }
}
