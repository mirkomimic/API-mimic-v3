<?php

namespace App\Controllers\Auth;

use App\Mimic\Auth;
use App\Database\DB;
use App\Http\Request;
use App\Models\User;
use App\Http\Response;
use App\Models\Session;
use App\Utilities\Query;
use App\Http\Requests\LoginRequest;

class SessionController
{

  public function login()
  {
    $request = Request::getArray();

    if (!$user = $this->getUserByEmail($request->email)) {

      $response = new Response();
      $response->set_httpStatusCode(409);
      $response->set_success(false);
      $response->set_message("Email is not correct");
      $response->send();
      exit;
    }

    $user->deleteToken();

    $accesstoken = $user->createToken();

    Session::create([
      'userId' => $user->id,
      'accesstoken' => $accesstoken,
      'accessexpiry' => 'DATE_ADD(NOW(), INTERVAL 28000 SECOND)'
    ]);

    Auth::login($user);

    $response = new Response();
    $response->set_httpStatusCode(201);
    $response->set_success(true);
    $response->set_message("User logged in, access token created");
    $response->set_data(['accesstoken' => $accesstoken]);
    $response->send();
    exit;
  }

  public function logout()
  {

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

    if (!self::check($accesstoken)) {
      $response = new Response();
      $response->set_httpStatusCode(401);
      $response->set_success(false);
      $response->set_message("Access token not valid or it has expired");
      $response->send();
      exit;
    }

    Auth::user()->deleteToken();

    Auth::logout();

    $response = new Response();
    $response->set_httpStatusCode(201);
    $response->set_success(true);
    $response->set_message("User logged out.");
    $response->send();
    exit;
  }

  public static function check(string $token): bool
  {
    $user = Auth::user();

    if (isset($user) && $user->token() !== null && $user->token() == $token && ($user->tokenExpiryTime() > time()))
      return true;
    else {
      return false;
    }
  }

  public function getUserByEmail(string $email)
  {
    $user = Query::select()->table('users')->where('email', '=', $email)->getModel();

    return $user;
  }
}
