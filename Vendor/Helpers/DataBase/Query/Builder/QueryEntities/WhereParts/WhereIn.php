<?php


namespace Helpers\DataBase\Query\Builder\QueryEntities\WhereParts;


use Helpers\DataBase\Query\Builder\QueryItem;
use Helpers\DataBase\Query\QueryBuilder;

final class WhereIn
{
    protected $conditionIsNot;
    protected $key;
    protected $values;
    protected $queryItem;

    public static function in ($key, array $values = [], QueryItem $item = null) {
        return new self($key, $values, $item, false);
    }

    public static function notIn ($key, array $values = [], QueryItem $item = null) {
        return new self($key, $values, $item, true);
    }

    public function isNot () :bool {
        return $this->conditionIsNot;
    }

    public function isSubQuery () {
        return (!is_null($this->queryItem));
    }

    public function getQueryItem () {
        return $this->queryItem;
    }

    public function getKey () {
        return $this->key;
    }

    public function getValues () {
        return $this->values;
    }

    private function __construct($key, array $values = [], QueryItem $item = null, $conditionIsNot = false)
    {
        $this->conditionIsNot = $conditionIsNot;
        $this->key = $key;
        $this->values = $values;
        $this->queryItem = $item;
    }
}