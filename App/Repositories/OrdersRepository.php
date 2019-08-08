<?php


namespace App\Repositories;


use App\Entities\Order;
use Helpers\Architecture\Adapters\IAdapter;
use Helpers\Architecture\Repository;
use Helpers\DataBase\Query;

class OrdersRepository extends Repository
{
    public function saveAndGetLastID (Order $item) {
        $tableName = $item::getNameClassInSnakeCase();
        $SQL = "INSERT INTO `$tableName`";

        $attributes = $item->getAttributes();
        $keys = implode(', ', array_keys($attributes));
        $values = "";
        $SQL .= " ($keys)";

        $prepareParams = [];
        foreach ($attributes as $key => $value) {
            $key = "{$key}_param_";
            $prepareParams[$key] = $value;
            $values .= " :{$key} ,";
        }
        $values = rtrim($values, ' ,');
        $SQL .= " VALUES ($values)";

        $query = (new Query());
        $stmt = $query->prepare($SQL);
        $stmt->execute($prepareParams);

        return $query->lastInsertId($item::$incrementName);
    }

    public function __construct(IAdapter $adapter = null)
    {
        parent::__construct($adapter);
        $this->setEntityClass(Order::class);
    }
}