<?php

use App\Controllers\Auth\SessionController;
use App\Http\Request;
use Seytar\Routing\Router;
use Illuminate\Support\Facades\Route;

require_once './vendor/autoload.php';

Router::bootstrap(function ($ex) {
  header('Content-Type: text/html; charset=utf-8');
  echo '404 - Page Not Found';
});

$request = 'req';

Route::get('/', function () {
  echo 'Hello world.';
});

Route::post('login', ['uses' => '\App\Controllers\Auth\SessionController@login']);
