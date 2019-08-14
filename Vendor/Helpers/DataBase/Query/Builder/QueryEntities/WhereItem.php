<?php

namespace Helpers\DataBase\Query\Builder\QueryEntities;

use Helpers\Architecture\Entity;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\IWhereType;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereBetween;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereIn;
use Helpers\DataBase\Query\Builder\QueryEntities\WhereParts\WhereType;
use Helpers\DataBase\Query\Builder\SQLBuilder;

class WhereItem extends Entity implements SQLBuilder
{
    public static $fillable = ['type', 'isJoin', 'key', 'value', 'in', 'operator', 'betweenParams'];

    /**
     * @param IWhereType|null $type
     * @return $this
     */
    public function setType(IWhereType $type = null)
    {
        if (is_null($type)) {
            $type = WhereType::firstCondition();
        }

        $this->setAttribute('type', $type);
        return $this;
    }

    public function setBetweenParams (WhereBetween $item) {
        $this->setAttribute('betweenParams', $item);
        return $this;
    }

    public function getBetweenParams () {
        return $this->getAttribute('betweenParams');
    }

    public function setIsJoin(bool $isJoin = false)
    {
        $this->setAttribute('isJoin', $isJoin);
        return $this;
    }

    public function isJoin()
    {
        return $this->getAttribute('isJoin');
    }

    public function setKey(string $key)
    {
        $this->setAttribute('key', $key);
        return $this;
    }

    public function setValue(string $value)
    {
        $this->setAttribute('value', $value);
        return $this;
    }

    public function setIn(WhereIn $item = null)
    {
        $this->setAttribute('in', $item);
        return $this;
    }
    public function getIn () {
        return $this->getAttribute('in');
    }

    public function getType(): WhereType
    {
        return $this->getAttribute('type');
    }

    public function getKey()
    {
        return $this->getAttribute('key');
    }

    public function getValue()
    {
        return $this->getAttribute('value');
    }

    public function setOperator(string $operator = "=")
    {
        $this->setAttribute('operator', $operator);
        return $this;
    }

    public function getOperator()
    {
        return $this->getAttribute('operator');
    }

    public function getSQL(): string
    {
        $result = " ";
        $type = $this->getType();
        if ($type->isFirstCondition()) {
            if ($this->isJoin()) {
                $result .= "ON";
            } else {
                $result .= "WHERE";
            }
        } else {
            $result .= $type->getType();
        }

        $key = $this->getKey();
        $operator = $this->getOperator();
        $value = $this->getValue();
        $inItem = $this->getIn();

        if (!is_null($key) && !is_null($value)) {
            $result .= "{$key} {$operator} {$value}";
        }

        if (!is_null($inItem) && !is_null($key)) {
            /**
             * @var $inItem WhereIn
            */
            $partNot = ($inItem->isNot()) ? "NOT" : "";
            $result .= " `{$key}` {$partNot} IN (";

            if (count($inItem->getValues())>0) {
                foreach ($inItem->getValues() as $value) {
                    $result .= "{$value},";
                }
                $result = rtrim($result, ',');
            }


            $result .= ")";
        }

        /**
         * @var $betweenParams WhereBetween
        */
        $betweenParams = $this->getBetweenParams();
        if (!is_null($betweenParams) && !is_null($key)) {
            $partNot = ($betweenParams->isNot()) ? "NOT" : "";
            $result .= " `{$key}` {$partNot} BETWEEN {$betweenParams->getStart()} AND {$betweenParams->getFinish()}";
        }

        return $result;
    }

    public function __construct()
    {
        $this
            ->setType()
            ->setOperator()
            ->setIsJoin();
    }
}