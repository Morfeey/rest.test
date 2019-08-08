<?php


namespace Helpers\DataBase;


use Helpers\Architecture\Entity;
use Helpers\IInnerEssence;
use Helpers\StringValue;

class QueryBuilder implements IInnerEssence
{
    public $query;
    public $statement;

    protected $selectFieldsLine;
    protected $from;
    protected $where;
    protected $orderBy;
    protected $offset;
    protected $limit;
    protected $fetchMode;

    protected $firstParams;

    protected $keyValueList;
    protected $queryTypes = [
        'isSelect' => false,
        'isInsert' => false,
        'isUpdate' => false,
        'isDelete' => false,
        'isTruncate' => false
    ];

    protected $lastInsertID;

    public function select(string ...$fields)
    {
        $lineFields = "";
        if (count($fields) != 0) {
            $lineFields = implode(', ', $fields);
        } else {
            $lineFields = "*";
        }

        $this->selectFieldsLine = $lineFields;
        $this
            ->setQueryType(__FUNCTION__)
            ->clearParams();
        return $this;
    }

    public function truncate () {
        $this->setQueryType(__FUNCTION__);
        return $this;
    }

    protected function whereConditionsBuilder(int $queryID = 0)
    {
        $sql = "";
        if (isset($this->where[$queryID])) {
            foreach ($this->where[$queryID] as $numCondition => $condition) {
                if ($numCondition === 0) {
                    $sql .= " WHERE";
                } else {
                    $isAnd = $condition['isAnd'];
                    if ($isAnd) {
                        $sql .= " AND";
                    } else {
                        $sql .= " OR";
                    }
                }
                $paramName = "{$condition['field']}_whereCondition_{$numCondition}_{$queryID}";
                $sql .= " `{$condition['field']}` {$condition['operator']} :$paramName";
                $this->setFirstParams($paramName, $condition['value'], $queryID);
            }
        }

        return $sql;
    }

    protected function setFirstParams($key, $value, $queryID = 0)
    {
        $this->firstParams[$queryID][$key] = $value;
        return $this;
    }

    public function from(string $from = null)
    {
        $this->from = $from;
        return $this;
    }

    protected function getQueryID () {
        $result = 0;
        if (count($this->keyValueList)>0) {
            $keyList = array_keys($this->keyValueList);
            $lastID = array_pop($keyList);
            $result = $lastID;
        }
        return $result;
    }

    public function where($field, $value, $operator = '=', $whereIsAnd = true)
    {
        $item = [
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'isAnd' => $whereIsAnd
        ];

        $queryID = $this->getQueryID();
        $this->where[$queryID][] = $item;
        return $this;
    }

    protected function setQueryType(string $type)
    {
        $types = $this->queryTypes;
        $type = (new StringValue("is_{$type}"))->toCamelCase(false)->getResult();
        foreach ($types as $key => $item) {
            $this->queryTypes[$key] = false;
        }
        $this->queryTypes[$type] = true;
        return $this;
    }

    protected function clearParams () {
        $this->where = [[]];
        $this->keyValueList = [];
        $this->firstParams = [[]];
        $this
            ->limit()
            ->offset();
        return $this;
    }

    public function insert(array $keyValue)
    {
        $this->keyValueList [] = $keyValue;
        $this->setQueryType(__FUNCTION__);
        return $this;
    }

    public function update(array $keyValue)
    {
        $this->keyValueList [] = $keyValue;
        $this->setQueryType(__FUNCTION__);
        return $this;
    }

    public function delete()
    {
        $this
            ->setQueryType(__FUNCTION__)
            ->clearParams();
        return $this;
    }

    public function execute()
    {

        $getQueryType = function () {
            return array_search(true, $this->queryTypes);
        };
        $getFirstSQLOnType = function () use ($getQueryType) {
            $queryType = $getQueryType();
            $result = "";
            switch ($queryType) {
                case "isSelect":
                    $result = "SELECT {$this->selectFieldsLine} FROM `{$this->from}`";
                    break;
                case "isUpdate":
                    $result = "UPDATE `{$this->from}` SET";
                    break;
                case "isDelete" :
                    $result = "DELETE FROM `{$this->from}`";
                    break;
                case "isInsert" :
                    $result = "INSERT INTO `{$this->from}`";
                    break;
                case "isTruncate" :
                    $result = "TRUNCATE `{$this->from}`";
                    break;
                default:
                    throw new \PDOException('Query not installed');
                    break;
            }
            return $result;
        };
        $getPDOParamOnValue = function ($value) {
            $result = \PDO::PARAM_STR;

            if (is_bool($value)) {
                $result = \PDO::PARAM_BOOL;
            } else if (is_int($value)) {
                $result = \PDO::PARAM_INT;
            }
            return $result;
        };
        $getSQLBuilders = function ($queryID = 0) {
            $result = "";
            $result .= $this->whereConditionsBuilder($queryID);
            $result .= $this->orderByConditionBuilder();
            $result .= $this->limitAndOffsetConditionBuilder();
            return $result;
        };

        $queryType = $getQueryType();

        $this->query->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->statement = $this->query;
        try {

            $this->query->beginTransaction();

            if ($queryType === "isSelect" || $queryType === "isDelete") {
                $sql = $getFirstSQLOnType();
                $sql .= $getSQLBuilders($this->getQueryID());
                $this->statement = $this->query->prepare($sql);
                foreach ($this->firstParams[$this->getQueryID()] as $key => $value) {
                    $type = $getPDOParamOnValue($value);
                    $this->statement->bindValue($key, $value, $type);
                }
                $this->statement->execute();
            } else if ($queryType === "isUpdate") {
                $sql = $getFirstSQLOnType();
                $getSQLByItem = function (array $item) {
                    $result = "";
                    foreach ($item as $key => $value) {
                        $result .= " `$key` = :{$key}_UPDATE_PARAM_, ";
                    }
                    $result = rtrim($result, ', ');
                    return $result;
                };

                foreach ($this->keyValueList as  $queryID => $item) {
                    $SQL = $sql;
                    $SQL .= $getSQLByItem($item);
                    $SQL .= $getSQLBuilders($queryID);
                    $this->statement = $this->query->prepare($SQL);
                    if (isset($this->firstParams[$queryID])) {
                        foreach ($this->firstParams[$queryID] as $key => $value) {
                            $type = $getPDOParamOnValue($value);
                            $this->statement->bindValue($key, $value, $type);
                        }
                    }
                    foreach ($item as $key => $value) {
                        $type = $getPDOParamOnValue($value);
                        $this->statement->bindValue("{$key}_UPDATE_PARAM_", $value, $type);
                    }

                    $this->statement->execute();
                }
            } else if ($queryType === "isInsert") {
                $sql = $getFirstSQLOnType();
                $getSQLByItem = function ($item) {
                    $keys = "";
                    foreach ($item as $key => $value) {
                        $keys .= "`{$key}`, ";
                    }
                    $keys = rtrim($keys, ', ');
                    $keys = "($keys)";
                    $values = "";
                    foreach ($item as $key => $value) {
                        $values .= ":{$key}_KEY_PARAM_INSERT_ , ";
                    }
                    $values = rtrim($values, ', ');
                    $values = "($values)";

                    $result = " $keys VALUES $values";
                    return $result;
                };
                foreach ($this->keyValueList as $item) {
                    $SQL = $sql;
                    $SQL .= $getSQLByItem($item);
                    $this->statement = $this->query->prepare($SQL);
                    foreach ($item as $key => $value) {
                        $type = $getPDOParamOnValue($value);
                        $this->statement->bindValue("{$key}_KEY_PARAM_INSERT_", $value, $type);
                    }

                    $this->statement->execute();
                }
            }else if ($queryType === "isTruncate") {
                $SQL = $getFirstSQLOnType();
                $this->statement = $this->query->prepare($SQL);
                $this->statement->execute();
            }
            $this->query->commit();
        } catch (\PDOException $exception) {
            $this->query->rollBack();
        }

        $this->clearParams();
        return $this;
    }

    public function orderBy($key, $order = 'ASC')
    {
        $this->orderBy = [
            'key' => $key,
            'order' => $order
        ];
        return $this;
    }

    protected function orderByConditionBuilder()
    {
        $result = "";
        if (!is_null($this->orderBy)) {
            $paramName = "{$this->orderBy['key']}_orderByCondition";
            $result = " ORDER BY {$this->orderBy['key']} :$paramName";
            $this->setFirstParams($paramName, $this->orderBy['order']);
        }
        return $result;
    }

    public function offset(int $int = null)
    {
        $this->offset = $int;
        return $this;
    }

    public function limit(int $int = null)
    {
        $this->limit = $int;
        return $this;
    }

    protected function limitAndOffsetConditionBuilder()
    {
        $result = "";
        if (!is_null($this->limit)) {
            $offset = (!is_null($this->offset)) ? $this->offset : 0;
            $paramLimit = "_LIMIT_FOR_BIND_";
            $paramOffset = "_OFFSET_FOR_BIND_";
            $result = " LIMIT :$paramOffset, :$paramLimit";
            $this
                ->setFirstParams($paramOffset, $offset)
                ->setFirstParams($paramLimit, $this->limit);
        }
        return $result;
    }

    public function getLastInsertID($name = null)
    {
        dd($this->query->lastInsertId());
        return (int)$this->query->lastInsertId($name);
    }

    public function setConnectName(string $name)
    {
        $this->query->setConnectName($name);
        return $this;
    }

    public function setFetchMode ($mode, $className = null, $constrtuctParams = []) {
        $this->fetchMode = [
            'mode' => $mode,
            'className' => $className,
            'construct' => $constrtuctParams
        ];
        return $this;
    }

    public function getResult()
    {
        $this->execute();
        if (!is_null($this->fetchMode)) {
            $this->statement->setFetchMode($this->fetchMode['mode'], $this->fetchMode['className'], $this->fetchMode['construct']);
        }

        $result = $this->statement->fetchAll();
        return $result;
    }

    public function __construct()
    {
        $this->query = new Query();
        $this->clearParams();
        $this->keyValueList = [];
        $this->statement = $this->query->prepare('');
        $this->from();
        $this->fetchMode = null;
    }

}