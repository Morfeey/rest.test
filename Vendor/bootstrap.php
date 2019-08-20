<?php

//include_once "functions.php";
//include_once "Helpers/Autoloader.php";
include_once "autoload.php";
use Helpers\Autoloader;
use Helpers\Path\Directory;
use Helpers\Path\Recursion;
use Helpers\Routing\Router;

//dd(
//    (new Directory())->getDirectories('*', Recursion::isOn())
//);
//
//(new Autoloader())
//    ->addDirectories(
//        Recursion::isOn(),
//        (new Directory())->addChild('Vendor', 'Helpers'),
//        (new Directory())->addChild('App')
//    )
//    ->start()
//;
Router::start();