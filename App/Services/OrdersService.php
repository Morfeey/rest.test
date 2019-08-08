<?php


namespace App\Services;


use App\Entities\Order;
use App\Entities\Product;
use Helpers\Architecture\Service;

class OrdersService extends Service
{
    public function getOrder (Product ...$products) :Order {
        $amount = 0;
        if (count($products)>0) {
            foreach ($products as $product) {
                $price = (int) $product->getAttribute('price');
                $amount += $price;
            }
        }else {
            throw new \Exception('Products count is 0', 228);
        }


        return
            (new Order())
                ->setAttribute('amount', $amount)
                ->setAttribute('status', 1);
    }

    public function __construct(array ...$entitiesParams)
    {
        parent::__construct(...$entitiesParams);
        $this
            ->setEntityClassName(Order::class)
            ->setEntitiesParams(...$entitiesParams);
    }
}