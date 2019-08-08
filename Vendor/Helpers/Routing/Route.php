<?php


namespace Helpers\Routing;


class Route
{
    protected static function routeReturn ($type, string $route, RouteItem $item = null) {
        $item = (is_null($item)) ? (new RouteItem()) : $item;
        $item
            ->setType($type)
            ->setRoute($route)
        ;
        return $item;
    }
    public static function get (string $route) {
        return self::routeReturn('GET', $route);
    }
    public static function post (string  $route) {
        return self::routeReturn('POST', $route);
    }
    public static function group (string $prefix, array $routeItems) {
        $result = [];
        $addPrefix = function (array $routeItems) use ($prefix, &$addPrefix) {
            $result = [];
            foreach ($routeItems as $item) {
                if (is_array($item)) {
                    $result[] = $addPrefix($item);
                }else {
                    $item->addPrefix($prefix);
                    $result[] = $item;
                }
            }
            return $result;
        };
        $result[] = $addPrefix($routeItems);
        return $result;
    }
}