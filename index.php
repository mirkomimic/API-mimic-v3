<?php

use Seytar\Routing\Router;
use Illuminate\Support\Facades\Route;

require_once './vendor/autoload.php';

session_start();

Router::bootstrap(function ($ex) {
  header('Content-Type: text/html; charset=utf-8');
  echo '404 - Page Not Found';
});

Route::get('/', function () {
  echo 'Hello world.';
});


// login
Route::post('login', ['uses' => '\App\Controllers\Auth\SessionController@login']);
// logout
Route::post('logout', ['uses' => '\App\Controllers\Auth\SessionController@logout']);
// register
Route::post('register', ['uses' => '\App\Controllers\UserController@store']);
// orders by page
Route::get('orders/page/{page}', ['uses' => '\App\Controllers\OrderController@index']);
// order by id
Route::get('order/{id}', ['uses' => '\App\Controllers\OrderController@show']);
// get orders
Route::get('orders', ['uses' => '\App\Controllers\OrderController@index']);
// post order
Route::post('orders', ['uses' => '\App\Controllers\OrderController@store']);
// post products
Route::post('products', ['uses' => '\App\Controllers\ProductController@store']);
// get products with filter
Route::get('products/page/{page}', ['uses' => '\App\Controllers\ProductController@index']);
