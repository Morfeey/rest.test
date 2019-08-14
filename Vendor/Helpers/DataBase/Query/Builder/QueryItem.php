<?php

namespace Helpers\DataBase\Query\Builder;

use Helpers\Collections\IKeyValue;
use Helpers\DataBase\DataBaseException;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereItem;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereType;

class QueryItem implements SQLBuilder
{
    /**
     * @var $params IKeyValue[]
    */
    protected $params;
    /**
     * @var $whereConditions WhereItem[]
    */
    protected $whereConditions;
    protected $from;

    protected $type;
    protected $typeIsReady;

    public function getSQL() :string
    {
        $result = "";

        if (!is_null($this->from)) {

        }else {
            throw new \Exception('From is null');
        }

        return $result;
    }

    public function addWhereCondition (WhereItem $item) {

        if (count($this->whereConditions)>1) {
            if ($item->getType()->isFirstCondition()) {
                $item->setType(WhereType::isAnd());
            }
        }

        $this->whereConditions[] = $item;
        return $this;
    }

    public function getWhereConditions () {
        return $this->whereConditions;
    }

    public function addParams (IKeyValue ...$keyValues) {
        foreach ($keyValues as $params) {
            $this->params[] = $params;
        }
        return $this;
    }

    public function setFrom (string $from) {
        $this->from = $from;
    }

    /**
     * @param string|null $type
     * @return $this
     * @throws DataBaseException
     */
    public function setType (string $type = null) {
        if (!$this->typeIsReady) {
            if (!is_null($type)) {
                $this->type = $type;
                $this->typeIsReady = true;
            }
        }else {
            throw new DataBaseException('Type is ready');
        }

        return $this;
    }

    public function getType () {
        return $this->type;
    }

    public function __construct(string $type = null)
    {
        $this->params = [];
        $this->from = null;
        $this->typeIsReady = false;
        $this->setType($type);
    }
}