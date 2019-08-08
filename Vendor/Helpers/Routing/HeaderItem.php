<?php


namespace Helpers\Routing;


use Helpers\StringValue;

class HeaderItem
{
    protected $key;
    protected $value;

    /**
     * @param mixed $key
     * @return self
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __construct(string $key, string $value)
    {
        $this
            ->setKey($key)
            ->setValue($value);
    }
}