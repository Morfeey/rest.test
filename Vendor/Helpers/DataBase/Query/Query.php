<?php

namespace Helpers\DataBase\Query;

use Helpers\DataBase\Config;

class Query extends \PDO
{
    protected $connectName;

    /**
     * @param string $name
     * @throws \Exception
     * @return self
     */
    public function setConnectName (string $name): self {
        self::__construct((new Config())->setConnectName($name));
        return $this;
    }

    /**
     * Query constructor.
     * @param Config|null $config
     * @throws \Exception
     */
    public function __construct(Config $config = null)
    {
        $config = (is_null($config)) ? new Config() : $config;

        $connect = $config->getConnect();
        $dsn = "{$connect->getDriver()}:host={$connect->getHost()};dbname={$connect->getDataBase()};charset={$connect->getCharset()}";
        $port = $connect->getPort();
        if (!is_null($port)) {
            $dsn .= ";port=$port";
        }
        parent::__construct($dsn, $connect->getUserName(), $connect->getPassword(), $config->getOptions());
    }
}