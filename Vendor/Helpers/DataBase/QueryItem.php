<?php


namespace Helpers\DataBase;


use Helpers\IInnerEssence;

class PrepareQueryItem implements IInnerEssence
{
    protected $params;
    protected $from;

    public function getResult()
    {
        $result = null;

        if (!is_null($this->from)) {

        }else {
            throw new \Exception('From is null');
        }

        return $result;
    }

    public function addParams (array ...$keyValue) {
        foreach ($keyValue as $params) {
            $this->params[] = $params;
        }
    }

    public function setFrom (string $from) {
        $this->from = $from;
    }

    public function __construct()
    {
        $this->params = [];
        $this->from = null;
    }
}