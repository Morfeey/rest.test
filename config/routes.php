<?php

use App\Controllers\MainController;
use App\Controllers\OrdersController;
use App\Controllers\ProductsController;
use Helpers\Routing\Route;

return [
    "options" => [],
    "routes" => [

        Route::get('404')
            ->setCallBack(function () {
            return "Страница не найдена";
        }),

        Route::get('/')
            ->setController(MainController::class)
            ->setMethod('index'),

        Route::group('order', [
            Route::get('/')
                ->setController(OrdersController::class)
                ->setMethod('index'),

            Route::post('create')
                ->setController(OrdersController::class)
                ->setMethod('create'),
            Route::post('pay')
                ->setController(OrdersController::class)
                ->setMethod('pay')
        ]),

        Route::group('products', [

            Route::get('/')
                ->setController(ProductsController::class)
                ->setMethod('index'),

            Route::post('generate_start_entities')
                ->setController(ProductsController::class)
                ->setMethod('generateStartEntities'),
            Route::get('get_all')
                ->setController(ProductsController::class)
                ->setMethod('getAll')
        ])
    ]
];