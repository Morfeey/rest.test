<?php


namespace Helpers\Routing;


use Helpers\Path\PathHandler;

class Request
{
    protected $requestURI;
    protected $type;
    protected $params;
    protected $headers;
    protected $status;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array|false|string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return array|false
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getRequestURI()
    {
        return $this->requestURI;
    }

    public function __construct()
    {
        $this->requestURI = (new PathHandler($_SERVER['REDIRECT_URL']))->addLastSlash()->getResult();
        $this->type = $_SERVER['REQUEST_METHOD'];
        $this->params = ($this->type === "POST") ? $_POST : ($this->type === "GET") ? $_GET : null;
        $this->params = (is_null($this->params)) ? [] : $this->params;
        $this->headers = (new Headers())->getAll();
        $this->status = $_SERVER['REDIRECT_STATUS'];
    }
}