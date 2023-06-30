<?php

namespace App\Controllers;

use App\Http\Url;
use App\Http\Request;
use App\Http\Response;
use App\Models\Product;
use App\Utilities\Query;
use App\Utilities\Paginator;
use App\Resources\ProductResource;
use App\Controllers\Auth\SessionController;

class ProductController
{

  public static function index(int $page = 1)
  {
    $params = Url::getParams();

    $products = Product::filter($params);
    $products = ProductResource::collection($products);
    $products = Paginator::paginate($products, $page, 5);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($products);
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

    $product = Product::create([
      'name' => $request->name,
      'price' => $request->price
    ]);

    $data = new ProductResource($product);

    $response = new Response();
    $response->set_httpStatusCode(200);
    $response->set_success(true);
    $response->set_data($data);
    $response->set_message("Product Added");
    $response->send();
    exit;
  }

  public static function checkIfProductsExists(array $array)
  {
    $productIds = array_column($array, 'id');
    $query = Query::select('id')->table('products')->getArray();
    $query = array_column($query, 'id');

    if (!empty(array_diff($productIds, $query))) {
      return false;
    }

    return true;
  }
}
