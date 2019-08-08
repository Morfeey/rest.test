<?php


namespace App\Services;


use App\Entities\Product;
use Helpers\Architecture\Service;

class ProductsService extends Service
{
    public function generateEntities ($count = 20) {
        $params = [];
        for ($i=0;$i<$count;$i++) {
            $name = rand(0, ($i+1) * 10000);
            $price = rand (1, 100000);
            $params [] =  [
                'name' => "Product №{$name}",
                'price' => $price
            ];
        }
        $this->setEntitiesParams(...$params);
        return $this;
    }

    public function __construct(array ...$entitiesParams)
    {
        parent::__construct($entitiesParams);
        $this
            ->setEntityClassName(Product::class)
            ->setEntitiesParams(...$entitiesParams);
    }
}