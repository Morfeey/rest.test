<?php

include_once "functions.php";
include_once "Helpers/Autoloader.php";
use Helpers\Autoloader;
use Helpers\Path\Directory;
use Helpers\Path\Recursion;
use Helpers\Routing\Router;

(new Autoloader())
    ->addDirectories(
        Recursion::isOn(),
        (new Directory())->addChild('Vendor', 'Helpers'),
        (new Directory())->addChild('App')
    )
    ->start()
;
Router::start();