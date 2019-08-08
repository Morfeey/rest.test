<?php


namespace Helpers\Routing;


use Helpers\Path\PathHandler;
use Helpers\StringValue;

class RouteItem
{
    protected $type;
    protected $prefix;
    protected $route;
    protected $params;
    protected $controller;
    protected $method;
    protected $callBack;

    /**
     * @return mixed
     */
    public function getClosure() : \Closure
    {
        $result = null;
        if (!is_null($this->callBack) && is_callable($this->callBack)){
            $result = function () {
                return call_user_func($this->callBack, $this->params);
            };
        }else if (!is_null($this->controller) && $this->method) {
            $result = function () {
                return call_user_func_array([$this->controller, $this->method], $this->params);
            };
        }

        return $result;
    }

    /**
     * @param mixed $callBack
     * @return  self
     */
    public function setCallBack($callBack): self
    {
        $this->callBack = $callBack;
        return $this;
    }

    /**
     * @param mixed $controller
     * @return self
     */
    public function setController($controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @param mixed $method
     * @return self
     */
    public function setMethod($method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param mixed $params
     * @return self
     */
    public function setParams(...$params): self
    {
        foreach ($params as $param) {
            if (!is_null($param)) {
                $this->params[] = $param;
            }
        }

        if (count($params) === 0) {
            $this->params = [];
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        $routeHandler = new PathHandler();

        if (!is_null($this->prefix)) {
            $routeHandler->addChild($this->prefix)->subLastSlash();
        }
        if (!is_null($this->route)) {
            $routeHandler->addChild($this->route);
        }

        $result =  $routeHandler
            ->addFirstSlash()
            ->addLastSlash()
            ->getResult();
        return $result;
    }

    /**
     * @param mixed $route
     * @return self;
     */
    public function setRoute(string $route = null): self
    {
        $this->route = $route;
        return $this;
    }

    public function addPrefix (string ...$prefix): self {
        $prefixHandler = new PathHandler();
        foreach ($prefix as $item) {
            $prefixHandler->addChild($item)->subLastSlash();
        }

        if (!is_null($this->prefix)) {
            $prefixHandler->addChild($this->prefix);
        }

        $result = $prefixHandler->addLastSlash()->getResult();
        $this->setPrefix($result);
        return $this;
    }

    /**
     * @param mixed $prefix
     * @return self
     */
    protected function setPrefix(string $prefix = null): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param mixed $type
     * @return self
     */
    public function setType(string $type = null): self
    {
        if (!is_null($type)) {
            $strType = (new StringValue($type))->toUp()->getResult();
            $this->type = $strType;
        }else {
            $this->type = $type;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function __construct()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key=>$value) {
            $keyMethod = (new StringValue("set_{$key}"))->toCamelCase(false)->getResult();
            $this->$keyMethod(null);
        }
        $this->setParams();
    }
}