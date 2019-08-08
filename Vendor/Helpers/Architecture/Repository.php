<?php

namespace Helpers\Architecture;

use Helpers\Architecture\Adapters\IAdapter;
use Helpers\Architecture\App\Config;
use Helpers\IInnerEssence;

class Repository implements IInnerEssence
{
    public $adapter;

    public function from (string $from) {
        $this->adapter->from($from);
        return $this;
    }

    public function all () {
        $this->adapter->select();
        return $this;
    }

    public function getByID (...$ids) {
        $this->adapter->getByID(...$ids);
        return $this;
    }

    public function where () {}

    public function save (Entity ...$entities) {
        $this->adapter->setEntities(...$entities);
        $this->adapter->save();
        return $this;
    }

    public function update (Entity ...$entities) {
        $this->adapter->setEntities(...$entities);
        $this->adapter->update();
        return $this;
    }

    public function delete (Entity ...$entities) {
        $this->adapter->setEntities(... $entities);
        $this->adapter->delete();
        return $this;
    }

    public function truncate () {
        $this->adapter->truncate();
        return $this;
    }

    /**
     * @return Entity[]
     */
    public function getResult()
    {
        $result = $this->adapter->getResult();
        return $result;
    }

    public function setEntityClass (string $className) {
        $this->adapter->setEntityClass($className);
        return $this;
    }

    public function __construct(IAdapter $adapter = null)
    {
        $this->adapter = (is_null($adapter)) ? (new Config())->getAdapter() : $adapter;
    }
}