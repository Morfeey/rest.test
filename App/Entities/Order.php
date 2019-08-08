<?php


namespace App\Entities;


use App\Repositories\ProductsRepository;
use Helpers\Architecture\Entity;

class Order extends Entity
{
    public static $fillable = ['status', 'amount'];

    public function getStatus () {
        $result = null;
        $status = $this->getAttribute('status');
        $statusIDS = [
            1 => "Новый",
            2 => "Оплачено"
        ];
        if (isset($statusIDS[(int)$status])) {
            $result = $statusIDS[(int)$status];
        }
        return $result;
    }

    public function getAmount() {
        return (int) $this->getAttribute('amount');
    }
}