<?php


namespace Helpers\DataBase;


use Helpers\StringValue;

class ConnectItem
{
    protected $host;
    protected $prefix;
    protected $dataBase;
    protected $userName;
    protected $password;
    protected $driver;
    protected $charset;
    protected $port;
    protected $options;


    /**
     * @param mixed $port
     * @return self
     */
    public function setPort($port = null): self
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param mixed $options
     * @return self
     */
    public function setOptions($options = null): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param mixed $prefix
     * @return self
     */
    public function setPrefix($prefix = null): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param mixed $charset
     * @return self
     */
    public function setCharset($charset = null): self
    {
        $charset = (is_null($charset)) ? 'utf8' : $charset;
        $this->charset = $charset;
        return $this;
    }

    /**
     * @param mixed $dataBase
     * @return self
     */
    public function setDataBase($dataBase = null): self
    {
        $dataBase = (is_null($this->prefix)) ? "{$dataBase}" : "{$this->prefix}_{$dataBase}";
        $this->dataBase = $dataBase;
        return $this;
    }

    /**
     * @param mixed $driver
     * @return self
     */
    public function setDriver($driver = null): self
    {
        $driver = (is_null($driver)) ? 'mysql' : $driver;
        $this->driver = $driver;
        return $this;
    }

    /**
     * @param mixed $host
     * @return self
     */
    public function setHost($host = null): self
    {
        $host = (is_null($host)) ? 'localhost' : $host;
        $this->host = $host;
        return $this;
    }

    /**
     * @param mixed $password
     * @return self
     */
    public function setPassword($password = null): self
    {
        $password = (is_null($password)) ? "" : $password;
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $userName
     * @return self
     */
    public function setUserName($userName = null): self
    {
        $userName = (is_null($userName)) ? "" : $userName;
        $userName = (is_null($this->prefix)) ? "{$userName}" : "{$this->prefix}_{$userName}";
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @return mixed
     */
    public function getDataBase()
    {
        return $this->dataBase;
    }

    /**
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $result = (is_null($this->options)) ? [] : (is_array($this->options)) ? $this->options : [];
        return $result;
    }

    public function __construct(array $params = [])
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            $method = (new StringValue('set_'.$key))->toCamelCase(false)->getResult();
            $value = (isset($params[$key])) ? $params[$key] : null;
            if ($value === "" || empty($value)) {
                $value = null;
            }
            $this->$method($value);
        }
    }
}