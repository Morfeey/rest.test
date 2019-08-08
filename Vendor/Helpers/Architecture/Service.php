<?php


namespace Helpers\Architecture;


class Service
{
    protected $entityName;
    protected $entities;

    public function getEntityClassName () {
        return $this->entityName;
    }

    public function setEntityClassName (string $className = null) {
        $this->entityName = (is_null($className)) ? Entity::class : $className;
        return $this;
    }

    public function setEntitiesParams (array ...$entitiesParams) {
        $this->entities = [];
        $this->addEntitiesParams(...$entitiesParams);
        return $this;
    }

    public function addEntitiesParams (array ...$entitiesParams) {
        foreach ($entitiesParams as $params) {
            $entityClass = $this->getEntityClassName();
            $this->entities [] =
                (new $entityClass())
                    ->setAttributes($params);
        }
        return $this;
    }

    public function getEntities () {
        return $this->entities;
    }

    public function __construct(array ...$entitiesParams)
    {
        $this
            ->setEntityClassName()
            ->setEntitiesParams(...$entitiesParams);
    }
}