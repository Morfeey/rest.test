<?php


namespace App\Repositories;


use App\Entities\Product;
use Helpers\Architecture\Adapters\IAdapter;
use Helpers\Architecture\Repository;

class ProductsRepository extends Repository
{
    public function __construct(IAdapter $adapter = null)
    {
        parent::__construct($adapter);
        $this->setEntityClass(Product::class);
    }
}