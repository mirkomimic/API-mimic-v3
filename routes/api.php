<?php

use App\Http\Url;
use App\Http\Route;
use App\Mimic\Auth;
use App\Http\Response;
use App\Http\Requests\HttpRequest;
use App\Controllers\UserController;
use App\Http\Requests\LoginRequest;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Http\Requests\RegisterRequest;
use App\Controllers\Auth\SessionController;

require_once '../vendor/autoload.php';

session_start();

$request = file_get_contents('php://input');
// $params = Url::getParams();
// var_dump($params);

// login
Route::post('login', [SessionController::class, 'login'], [new LoginRequest($request)]);
// logout
Route::post('logout', [SessionController::class, 'logout']);
// register
Route::post('register', [UserController::class, 'store'], [new RegisterRequest($request)]);
// order by id
Route::get('orders/' . $_GET['id'], [OrderController::class, 'show'], [$_GET['id']]);
// orders
// Route::get('orders', [OrderController::class, 'index'], []);
