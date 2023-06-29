<?php

namespace App\Controllers;

use App\Models\Product;
use App\Utilities\Query;
use App\Utilities\Paginator;
use App\Resources\ProductResource;

class ProductController
{

  public static function index($request, int $page = 1)
  {
    $products = Product::filter($request);
    $products = ProductResource::collection($products);
    $products = Paginator::paginate($products, $page, 5);

    return $products;
  }

  public static function store($request)
  {
    $product = Product::create([
      'name' => $request->name,
      'price' => $request->price
    ]);

    return new ProductResource($product);
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
