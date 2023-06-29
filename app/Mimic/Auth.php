<?php

namespace App\Mimic;

use App\Models\User;

class Auth
{

  public static function login(User $user)
  {
    $_SESSION['user'] = $user;
  }

  public static function user()
  {
    return $_SESSION['user'] ?? null;
  }

  public static function logout()
  {
    if (isset($_SESSION['user']))
      unset($_SESSION['user']);
  }
}
