<?php


namespace Helpers\Routing;

class Router
{
    protected $config;


    /**
     * @return RouteItem[]
     */
    public function getRouteList() {
        $routeList = $this->config->getRouteList();
        $getIterableRecurseRouteList = function ($routeList) use (&$getIterableRecurseRouteList) {
            $result = [];
            foreach ($routeList as $item) {
                if (is_array($item)) {
                    foreach ($getIterableRecurseRouteList($item) as $Item) {
                        $result[] = $Item;
                    }
                }else {
                    $result[] = $item;
                }
            }
            return $result;
        };
        $result = $getIterableRecurseRouteList($routeList);
        return $result;
    }

    public static function start (Config $config = null, bool $Jsonable = true) {
        $routesList = (new self($config))->getRouteList();
        $currentRequest = (new Request());
        $temp = [];

        foreach ($routesList as $item) {
            if ($item->getRoute() === $currentRequest->getRequestURI() && $item->getType() === $currentRequest->getType()) {
                $item->setParams(...$currentRequest->getParams());
                $callBack = call_user_func($item->getClosure());

                if ($Jsonable) {
                    if (is_array($callBack) || is_object($callBack)) {
                        $callBack = json_encode($callBack, JSON_PRETTY_PRINT);
                    }
                }
                return print $callBack;
            }
        }
        if (count($temp) === 0) {
            return (new self($config))->redirect('/404');
        }
    }

    public function redirect (string $route) {
        (new Headers())
            ->addHeader('Location', $route)
            ->send();
        return $this;
    }

    public function __construct(Config $config = null)
    {
        $this->config = (is_null($config)) ? new Config() : $config;
    }
}