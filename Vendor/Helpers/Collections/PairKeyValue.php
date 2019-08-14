<?php


namespace Helpers\Collections;


final class PairKeyValue implements IKeyValue
{
    protected $key;
    protected $value;

    public function getKey () {
        return $this->key;
    }

    public function getValue () {
        return $this->value;
    }

    public static function set ($key, $value) {
        return new self($key, $value);
    }

    private function __construct ($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }
}