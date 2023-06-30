<?php

namespace App\Traits;

use App\Database\Db;
use App\Utilities\Query;
use Exception;

trait HasTable
{
  private static $class = __CLASS__;

  public static function all()
  {
    $table = self::class::TABLE;
    $objects = Query::select()->table($table)->getModels();

    return $objects;
  }

  public static function find(int $id): object
  {
    $table = self::class::TABLE;
    $object = Query::select()->table($table)->where('id', '=', $id)->getModel();

    if ($object == null)
      throw new Exception('Object with id: ' . $id . 'not found');

    return $object;
  }

  public static function create(array $array)
  {
    try {
      $conn = Db::connectDB();
      $table = self::class::TABLE;
      $fields = "";
      $values = "";
      if (!(new self)->validateKeys($array)) {
        throw new Exception("Keys do not match");
      }

      foreach ($array as $key => $value) {
        $fields .= $key . ", ";
        if (str_starts_with($value, 'DATE_ADD')) {
          $values .= $value . ", ";
        } else {
          $values .= "'" . $value . "'" . ", ";
        }
      }

      $fields = rtrim($fields, ', ');
      $values = rtrim($values, ', ');

      $query = Query::insert()->table($table)->fields($fields)->values($values)->getQuery();
      $conn->query($query);

      $id = $conn->insert_id;

      return self::find($id);
    } catch (Exception $ex) {
      echo $ex->getMessage();
    }
  }

  public function update(array $array): object
  {
    try {
      $conn = Db::connectDB();
      $id = $this->id;
      $table = self::class::TABLE;

      if (!$this->validateKeys($array)) {
        throw new Exception("Keys do not match");
      }

      $query = Query::update()->table($table)->set($array)->where('id', '=', $id)->getQuery();
      $conn->query($query);

      return self::find($id);
    } catch (Exception $ex) {
      echo $ex->getMessage();
    }
  }

  public function delete()
  {
    $conn = Db::connectDB();
    $query = Query::delete()->table(self::class::TABLE)->where('id', '=', $this->id);
    $conn->query($query);
  }

  // name: ProductName
  // price: 1000
  // sort: priceAsc
  public static function filter(array $params)
  {
    if (!(new self)->validateFilter($params)) {
      throw new Exception('Wrong filter parameters');
    }

    $table = self::class::TABLE;
    $query = Query::select()->table($table);

    $variables = get_class_vars(self::$class);
    $variables = array_keys($variables);

    foreach ($params as $key => $value) {
      if ($key !== 'sort' && !str_contains($key, 'min') && !str_contains($key, 'max')) {
        $query->where($key, 'LIKE', '%' . $value . '%');
      } elseif ($key == 'sort') {
        $field = '';
        $sort = '';

        if (str_ends_with($value, 'Asc')) {
          $field = substr($value, 0, -3);
          $sort = 'ASC';
        } elseif (str_ends_with($value, 'Desc')) {
          $field = substr($value, 0, -4);
          $sort = 'DESC';
        }

        if (!in_array($field, $variables)) {
          throw new Exception('Wrong sort value');
        }

        $query->orderBy($field, $sort);
      }
    }

    return $query->getModels();
  }

  private function validateKeys(array $array): bool
  {
    $variables = get_class_vars(self::$class);
    $variables = array_keys($variables);
    unset($variables[array_search('class', $variables)]);
    $keys = array_keys($array);

    if (!empty(array_diff($keys, $variables)))
      return false;

    return true;
  }

  private function validateFilter(array $params): bool
  {
    $variables = get_class_vars(self::$class);
    $variables = array_keys($variables);
    array_push($variables, 'sort');
    unset($variables[array_search('class', $variables)]);

    $keys = array_keys($params);
    if (!empty(array_diff($keys, $variables)))
      return false;

    return true;
  }
}
