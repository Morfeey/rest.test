<?php


namespace Helpers\Architecture\Adapters;


use Helpers\Architecture\Entity;
use Helpers\DataBase\QueryBuilder;

class MySQLAdapter implements IAdapter
{
    /**
     * @var $queryBuilder QueryBuilder
    */
    protected $queryBuilder;

    /**
     * @var $entities Entity[]
    */
    protected $entities;
    protected $tableName;
    protected $entityClassName;
    protected $from;

    public function setEntityClass(string $className = null)
    {
        $this->entityClassName = (is_null($className)) ? Entity::class : $className;
        return $this;
    }

    public function where(string $field, $value, string $operator = '=', bool $isAnd = true)
    {
        $this->setQueryBuilderStartCondition();
        $this->queryBuilder->where($field, $value, $operator, $isAnd);
        return $this;
    }

    public function save()
    {
        $this->setQueryBuilderStartCondition();
        foreach ($this->entities as $entity) {
            $attributes = $entity->getAttributes();
            $this->queryBuilder->insert($attributes);
        }
        $this->queryBuilder->execute();
        return $this;
    }
    public function update()
    {
        $this->setQueryBuilderStartCondition();
        foreach ($this->entities as $entity) {
            $increment = $entity->getAttribute($entity::$incrementName);
            if (!is_null($increment)) {
                $attributes = $entity->getAttributes();
                unset($attributes[$entity::$incrementName]);
                $this
                    ->queryBuilder
                    ->update($attributes)
                    ->where($entity::$incrementName, $increment);
            }else {
                $className = get_class($entity);
                throw new \Exception("Update is fail, increment ({$entity::$incrementName}) not found. ( entity ({$className}))");
            }
        }
        $this->queryBuilder->execute();
        return $this;
    }
    public function delete()
    {
        $this->setQueryBuilderStartCondition();
        foreach ($this->entities as $entity) {
            $increment = $entity->getAttribute($entity::$incrementName);
            if (!is_null($increment)) {
                $this
                    ->queryBuilder
                    ->delete()
                    ->where($entity::$incrementName, $increment)
                    ->execute();
                ;
            }else {
                $className = get_class($entity);
                throw new \Exception("Delete is fail, increment ({$entity::$incrementName}) not found. ( entity ({$className}))");
            }
        }
        return $this;
    }

    public function truncate () {
        $this->setQueryBuilderStartCondition();
        $this->queryBuilder->truncate()->execute();
        return $this;
    }

    public function setEntities(Entity ...$entities)
    {
        foreach ($entities as $entity) {
            $this->entities[] = $entity;
        }
        return $this;
    }

    public function getByID (...$ids) {
        $className = $this->entityClassName;
        $incrementName = $className::$incrementName;

        $this->select();
        foreach ($ids as $id) {
            $this->where($incrementName, $id, '=', false);
        }
        return $this;
    }

    public function select(string ...$fields)
    {
       $this->setQueryBuilderStartCondition();
       $this->queryBuilder->select(...$fields);
       return $this;
    }

    public function getResult()
    {
        $this->setQueryBuilderStartCondition();
        $this->queryBuilder->setFetchMode(\PDO::FETCH_CLASS, $this->entityClassName);
        $result = $this->queryBuilder->getResult();
        return $result;
    }

    public function from(string $from = null)
    {
        $this->from = (is_null($from)) ? $this->getTableName() : $from;
        return $this;
    }

    public function getTableName () {
        $result = null;

        if (!is_null($this->tableName)) {
            $result = $this->tableName;
        }else if (!is_null($this->entityClassName)) {
            $result = classNameToNameTableModel($this->entityClassName);
        }

        return $result;
    }

    protected function setQueryBuilderStartCondition () {
        if (is_null($this->queryBuilder)) {
            $this->queryBuilder = new QueryBuilder();
        }
        $this->queryBuilder->from($this->getTableName());
        return $this;
    }

    public function __construct()
    {
        $this->queryBuilder = null;
        $this
            ->setEntities()
            ->setEntityClass();
    }
}