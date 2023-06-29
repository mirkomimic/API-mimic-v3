<?php

namespace App\Controllers;

use App\Mimic\Auth;
use App\Models\Order;
use App\Http\Response;
use App\Models\Product;
use App\Utilities\Query;
use App\Models\OrderItem;
use App\Utilities\Paginator;
use App\Resources\OrderResource;

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
  }

  public static function show(int $id)
  {
    echo 'ovde';
    return new OrderResource(Order::find($id));
  }

  public static function store(array $request)
  {

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

    return new OrderResource($order);
  }
}
