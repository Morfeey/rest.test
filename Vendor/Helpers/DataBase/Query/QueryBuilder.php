<?php


namespace Helpers\DataBase\Query;



use Helpers\Collections\IKeyValue;
use Helpers\Collections\PairKeyValue;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereItem;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\IWhereType;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereBetween;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereIn;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereType;
use Helpers\DataBase\Query\Builder\QueryItem;
use Helpers\DataBase\Query\Builder\SQLBuilder;

class QueryBuilder
{
    /**
     * @var $queryItemsList QueryItem[]
     */
    protected $queryItemsList;
    protected $transactionIsOn;

    public function transactionIsOn (bool $isOn = true) {
        $this->transactionIsOn = true;
        return $this;
    }

    protected function newItem (string $type) {
        $this->queryItemsList[] = (new QueryItem())->setType($type);
        return $this;
    }

    protected function getLastKeyItem () {
        return array_key_last($this->queryItemsList);
    }

    public function from (string $from, bool $toAllQueries = true) {
        if ($toAllQueries) {
            foreach ($this->queryItemsList as $item) {
                $item->setFrom($from);
            }
        }else {
            $this
                ->queryItemsList[$this->getLastKeyItem()]
                ->setFrom($from);
        }
        return $this;
    }

    public function truncate () {
        $this->newItem(__FUNCTION__);
        return $this;
    }

    public function insert (IKeyValue ...$keyValue) {
        $this->newItem(__FUNCTION__);
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addParams(...$keyValue);
        return $this;
    }

    public function select (string ...$keys) {
        $this->newItem(__FUNCTION__);
        if (count($keys)<1) {
            $this
                ->queryItemsList[$this->getLastKeyItem()]
                ->addParams(PairKeyValue::set(0, "*"));
        }else {
            foreach ($keys as $index => $key) {
                $this
                    ->queryItemsList[$this->getLastKeyItem()]
                    ->addParams(PairKeyValue::set($index, $key));
            }
        }
        return $this;
    }

    public function selectAlias (IKeyValue ...$keyValue) {
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addParams(...$keyValue);
        return $this;
    }

    public function delete () {
        $this->newItem(__FUNCTION__);
        return $this;
    }

    public function update (IKeyValue ...$keyValues) {
        $this->newItem(__FUNCTION__);
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addParams(...$keyValues);
        return $this;
    }

    protected function addDefaultWhere (string $key, $value, $operator = null, IWhereType $type = null) {
        $whereCondition =
            (new WhereItem())
                ->setKey($key)
                ->setValue($value);

        if (!is_null($type)) {
            $whereCondition->setType($type);
        }

        if (!is_null($operator)) {
            $whereCondition->setOperator($operator);
        }

        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addWhereCondition($whereCondition);
        return $this;
    }

    public function where (string $key, $value, $operator = null) {
        $this->addDefaultWhere($key, $value, $operator);
        return $this;
    }

    public function whereAnd (string $key, $value, $operator = null) {
        $this->addDefaultWhere($key, $value, $operator, WhereType::isAnd());
        return $this;
    }

    public function whereOr (string $key, $value, $operator = null) {
        $this->addDefaultWhere($key, $value, $operator, WhereType::isOr());
        return $this;
    }

    public function whereBetween ($key, $start, $finish) {
        $whereCondition =
            (new WhereItem())
                ->setKey($key)
                ->setBetweenParams(WhereBetween::between($start, $finish))
            ;

        $this->queryItemsList[$this->getLastKeyItem()]->addWhereCondition($whereCondition);
        return $this;
    }

    public function whereNotBetween (string $key, $start, $finish) {
        $whereCondition =
            (new WhereItem())
                ->setKey($key)
                ->setBetweenParams(WhereBetween::notBetween($start, $finish))
            ;
        $this->queryItemsList[$this->getLastKeyItem()]->addWhereCondition($whereCondition);
        return $this;
    }

    public function whereIn (string $key, array $listItems) {
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addWhereCondition(
                (new WhereItem())
                    ->setKey($key)
                    ->setIn(WhereIn::in($key, $listItems))
            );
        return $this;
    }

    public function whereNotIn (string $key, $listItems) {
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addWhereCondition(
                (new WhereItem())
                    ->setKey($key)
                    ->setIn(WhereIn::notIn($key, $listItems))
            );
        return $this;
    }

    public function whereInSubQuery (string $key, QueryItem $item) {
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addWhereCondition(
                (new WhereItem())
                    ->setKey($key)
                    ->setIn(WhereIn::in($key, [], $item))
            );
        return $this;
    }

    public function whereNotInSubQuery (string $key, QueryItem $item) {
        $this
            ->queryItemsList[$this->getLastKeyItem()]
            ->addWhereCondition(
                (new WhereItem())
                    ->setKey($key)
                    ->setIn(WhereIn::notIn($key, [], $item))
            );
        return $this;
    }

    public function orderBy () {}

    public function limit () {}

    public function offset () {}

    public function __construct(bool $transactionIsOn = true)
    {
        $this->transactionIsOn($transactionIsOn);
        $this->queryItemsList = [];
    }
}