<?php


namespace Helpers\DataBase;


class QueryBuilderNew
{
    protected $queryItemsList;
    protected $transactionIsOn;

    public function transactionIsOn (bool $isOn = true) {
        $this->transactionIsOn = true;
        return $this;
    }

    public function __construct(bool $transactionIsOn = true)
    {
        $this->transactionIsOn($transactionIsOn);
    }
}