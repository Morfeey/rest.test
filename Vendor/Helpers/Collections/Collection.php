<?php


namespace Helpers\Collections;


abstract class Collection implements \IteratorAggregate, \JsonSerializable, \Countable
{
    protected $values;

    public function count()
    {
        return count($this->values);
    }

    public function toArray () {
        return $this->values;
    }


    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    public function jsonSerialize()
    {
        return json_encode($this->values);
    }

    public function __toString()
    {
        return $this->jsonSerialize();
    }
}