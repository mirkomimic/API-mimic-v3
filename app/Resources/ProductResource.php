<?php

namespace App\Resources;

class ProductResource
{
  public $object = [];
  public function __construct($object)
  {
    return $this->object = [
      'id' => $object->id,
      'name' => $object->name,
      'price' => number_format($object->price, 2, ',', '.'),
    ];
  }

  public static function collection($objects)
  {
    $array['objects'] = [];

    foreach ($objects as $object) {
      $array['objects'][] = [
        'id' => $object->id,
        'name' => $object->name,
        'price' => number_format($object->price, 2, ',', '.'),
      ];
    }

    return $array;
  }
}
