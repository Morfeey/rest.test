<?php

namespace Helpers\Architecture;


class Entity
{
    public static function getNameClassInSnakeCase () {
        $className = get_called_class();
        return classNameToNameTableEntity($className);
    }

    public static $incrementName = "id";

    public static $fillable = [];

    public function getAttribute(string $fillableKey)
    {
        $result = (isset($this->$fillableKey)) ? $this->$fillableKey : null;
        return $result;
    }

    public function setAttribute(string $fillableKey, $value)
    {
        if (in_array($fillableKey, $this::$fillable) || $fillableKey === $this::$incrementName) {
            $this->$fillableKey = $value;
        }
        return $this;
    }

    public function setAttributes (array $params) {
        foreach ($params as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function getAttributes()
    {
        $result = [];
        $attributes = get_object_vars($this);
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this::$fillable) || $key === $this::$incrementName) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}