<?php


namespace App\Controllers;


use Helpers\Architecture\Controller;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereItem;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereBetween;
use Helpers\DataBase\Query\QueryBuilder;

class TestController extends Controller
{
    public function index () {

        dd(
            (new WhereItem())
                ->setKey('value')
                ->setBetweenParams(WhereBetween::notBetween(3, 10))
                ->getSQL()
        );

        return "test page";
    }
}