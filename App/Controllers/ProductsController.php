<?php


namespace App\Controllers;


use App\Repositories\ProductsRepository;
use App\Services\ProductsService;
use Helpers\Architecture\Controller;

class ProductsController extends Controller
{
    public function index()
    {
        return "page products";
    }

    public function generateStartEntities()
    {
        $productsEntities =
            (new ProductsService())
                ->generateEntities(20)
                ->getEntities();

        (new ProductsRepository())
            ->truncate()
            ->save(...$productsEntities);
        return true;
    }

    public function getAll()
    {
        return (new ProductsRepository())->all()->getResult();
    }

}