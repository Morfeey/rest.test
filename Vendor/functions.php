<?php

use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
use Helpers\StringValue;
use Helpers\Path\Directory;

/**
 * Dump and die
 * @param mixed ...$mixins
 */
function dd (... $mixins) {

    foreach ($mixins as $mixin) {
        print "<pre>";
        var_dump($mixin);
        print "</pre>";
    }
    die;
}

function classNameToNameTableEntity (string $className) {
    $parents = class_parents($className);
    $getClassNameWithoundNamespace = function ($className) {
        $explode = explode('\\', $className);
        return array_pop($explode);
    };
    $className = $getClassNameWithoundNamespace($className);
    $lastParent = array_pop($parents);
    $lastParent = $getClassNameWithoundNamespace($lastParent);
    $lastParent = (new StringValue($lastParent))->toSnakeCase();
    $stringClassName =
        (new StringValue($className))
            ->toLow()
            ->replace($lastParent->getResult() ,'')
            ->toSnakeCase()
    ;

    $chars = $stringClassName->getChars();
    $lastChar = array_pop($chars);
    $result = $stringClassName->getResult();
    if ($lastChar->toLow()->getResult() !== "s") {
        $result .= "s";
    }
    return $result;
}

function getRealIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return '0.0.0.0';
    }

    return $ip;
}

function publicDirectory (string ... $childPath) : Directory {
    $publicPath = (new Directory())->addChild('public');

    foreach ($childPath as $item) {
        $publicPath->addChild($item);
    }

    return $publicPath;
}

function publicPath (string ...$childPath) {
    return publicDirectory(... $childPath)->getResult();
}



function view (string $viewName, ...$param) {
    $pathViews = (new Directory())->addChild('resources', 'Views')->getResult();
    $pathCachedViews = (new Directory())->addChild('resources', 'Cache', 'Views')->getResult();
    $blade = new BladeInstance($pathViews, $pathCachedViews);

    return $blade->render($viewName, ...$param);
}