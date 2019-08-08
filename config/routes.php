<?php

use Helpers\Routing\Route;

return [
    "options" => [],
    "routes" => [

        Route::get('404')
            ->setCallBack(function () {
            return "Страница не найдена";
        }),

        Route::get('/')
            ->setController(\App\Controllers\MainController::class)
            ->setMethod('index'),

        Route::group('order', [
            Route::get('/')
                ->setController(\App\Controllers\OrdersController::class)
                ->setMethod('index'),

            Route::post('create')
                ->setController(\App\Controllers\OrdersController::class)
                ->setMethod('create'),
            Route::post('pay')
                ->setController(\App\Controllers\OrdersController::class)
                ->setMethod('pay')
        ]),

        Route::group('products', [

            Route::get('/')
                ->setController(\App\Controllers\ProductsController::class)
                ->setMethod('index'),

            Route::post('generate_start_entities')
                ->setController(\App\Controllers\ProductsController::class)
                ->setMethod('generateStartEntities'),
            Route::get('get_all')
                ->setController(\App\Controllers\ProductsController::class)
                ->setMethod('getAll')
        ])
    ]
];