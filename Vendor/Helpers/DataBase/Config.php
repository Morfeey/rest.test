<?php

namespace Helpers\DataBase;

use Helpers\Configuration\Configuration;

class Config extends Configuration
{
    protected $fullConfig;
    protected $connectName;

    public function setConnectName (string $name = null) {
        $this->connectName = $name;
        return $this;
    }

    /**
     * @param string|null $name
     * @return ConnectItem
     * @throws \Exception
     */
    public function getConnect ():ConnectItem {
        $connections = $this->fullConfig['connections'];
        $name =  $this->connectName;
        if (key_exists($name, $connections)) {
            $connect = $this->fullConfig['connections'][$name];
            return (new ConnectItem($connect));
        }else {
            throw new \Exception("Connect with name '{$name}' not found");
        }

    }

    public function getOptions (): array {
        $configOptions = (isset($this->fullConfig['options'])) ? $this->fullConfig['options'] : [];
        $configOptions = (is_array($configOptions) && !is_null($configOptions)) ? $configOptions : [];
        $connectOptions = [];
        try {$connectOptions = $this->getConnect()->getOptions();}catch (\Exception $exception) {}

        $result = array_replace_recursive($configOptions, $connectOptions);
        return $result;
    }

    /**
     * Config constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->fullConfig =
            $this
            ->setFileName('database')
            ->getFileConfig()
                ->getParser()
                    ->toArray();
        /**
         * Set default name connection
        */
        $name = $this->fullConfig['defaultConnect'];
        $this->setConnectName($name);
    }
}