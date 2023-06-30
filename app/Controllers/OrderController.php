<?php

namespace App\Controllers;

use App\Mimic\Auth;
use App\Http\Request;
use App\Models\Order;
use App\Http\Response;
use App\Models\Product;
use App\Utilities\Query;
use App\Models\OrderItem;
use App\Utilities\Paginator;
use App\Resources\OrderResource;
use App\Controllers\Auth\SessionController;

class OrderController
{

  public static function index(int $page = 1)
  {
    $orders = Order::all();
    $orders = OrderResource::collection($orders);
    $orders = Paginator::paginate($orders, $page, 5);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($orders);
    $response->send();
    exit;
  }

  public static function show(int $id)
  {
    $data = new OrderResource(Order::find($id));

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($data);
    $response->send();
    exit;
  }

  public static function store()
  {

    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

    if (!SessionController::check($accesstoken)) {
      $response = new Response();
      $response->set_httpStatusCode(401);
      $response->set_success(false);
      $response->set_message("Access token not valid or it has expired");
      $response->send();
      exit;
    }

    $request = Request::getArray();

    if (!ProductController::checkIfProductsExists($request)) {
      $response = new Response();
      $response->set_httpStatusCode(400);
      $response->set_success(false);
      $response->set_message("Product id does not exist.");
      $response->send();
      exit;
    }

    $order = Order::create([
      'userId' => Auth::user()->id,
      'value' => 0
    ]);

    $totalPrice = 0;
    foreach ($request as $prod) {
      $product = Product::find($prod->id);
      $totalPrice += $product->price;

      OrderItem::create([
        'orderId' => $order->id,
        'value' => $product->price,
        'productId' => $product->id
      ]);
    }

    $order = $order->update([
      'value' => $totalPrice
    ]);

    $data = new OrderResource($order);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_message("Order created!");
    $response->set_data($data);
    $response->send();
    exit();
  }
}
