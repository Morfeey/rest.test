<?php


namespace Helpers\Architecture\App;


use Helpers\Architecture\Adapters\IAdapter;
use Helpers\Configuration\Configuration;

class Config extends Configuration
{
    protected $fullConfig;

    public function getAdapter (): IAdapter {
        $className = $this->fullConfig['Adapter'];
        return new $className();
    }

    public function __construct()
    {
        parent::__construct();
        $this->fullConfig =
            $this
            ->setFileName('app')
            ->getFileConfig()
            ->getParser()
            ->toArray()
        ;
    }
}