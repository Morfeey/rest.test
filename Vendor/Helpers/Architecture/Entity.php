<?php

namespace Helpers\Architecture;


class Entity
{
    public static function getNameClassInSnakeCase () {
        $className = get_called_class();
        return classNameToNameTableEntity($className);
    }

    private $attributes = [];

    public static $incrementName = "id";

    public static $fillable = [];

    public function getAttribute(string $fillableKey)
    {
        return $this->__get($fillableKey);
    }

    public function setAttribute(string $fillableKey, $value)
    {
        return $this->__set($fillableKey, $value);
    }

    public function setAttributes (array $params) {
        foreach ($params as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this::$fillable) || $name === $this::$incrementName) {
            $this->$name = $value;
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    public function __get($name)
    {
        $result = null;
        if (in_array($name, $this->attributes)) {
            $result = $this->attributes[$name];
        }
        return $result;
    }

    public function __isset($name)
    {
        return in_array($name, $this->attributes);
    }

    public function __unset($name)
    {
        unset($this->$name);
        unset($this->attributes[$name]);
        return $this;
    }

}