<?php


namespace Helpers\Collections;


abstract class Collection implements \IteratorAggregate, \JsonSerializable, \Countable, \ArrayAccess
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
    
    public function offsetExists($offset)
    {
        return key_exists($offset, $this->values);
    }

    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
        return $this;
    }
}