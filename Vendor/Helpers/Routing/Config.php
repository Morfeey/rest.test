<?php


namespace Helpers\Routing;


use Helpers\Configuration\Configuration;
use Helpers\Configuration\ParserConfig\DefaultParser;

class Config extends Configuration
{
    protected $routeList;

    public function getRouteList () {
        return $this->routeList;
    }

    public function __construct()
    {
        parent::__construct();
        $config = $this->setFileName('routes')
            ->getFileConfig()
            ->getParser()
            ->toArray()
            ;
        $this->routeList = (isset($config['routes'])) ? $config['routes'] : [];
    }
}