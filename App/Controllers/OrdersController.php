<?php


namespace App\Controllers;


use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Services\OrdersService;

class OrdersController
{
    public function index () {
        return "order page";
    }

    public function create (...$ids) {
        $products = (new ProductsRepository())->getByID(...$ids)->getResult();
        $order = (new OrdersService())->getOrder(...$products);
        $orderID = (new OrdersRepository())->saveAndGetLastID($order);
        return $orderID;
    }

    public function pay ($id, $amount) {
        $orders = (new OrdersRepository())->getByID($id)->getResult();
        if (count($orders)>0) {
            $order = $orders[0];
            $orderAmount = $order->getAttribute('amount');
            $orderStatus = $order->getAttribute('status');

            if ($orderAmount === $amount && $orderStatus == 1) {
                $getStatusURL  = function ($url) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_exec($ch);
                    $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    return $http_code;
                };
                if ($getStatusURL('https://ya.ru') === 200) {
                    $order->setAttribute('status', 2);
                    (new OrdersRepository())->update($order);
                }
            }else {
                throw new \Exception("Order status is not new or amount not true");
            }
        }else {
            throw new \Exception("Order not found");
        }
        return true;
    }
}