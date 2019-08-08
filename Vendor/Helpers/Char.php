<?php


namespace Helpers;



class Char implements IInnerEssence
{
    protected $char;

    public function toUp () :self {
        $this->char = strtoupper($this->char);
        return $this;
    }

    public function isUp () {
        return
            ($this->getResult() === (new self($this->getResult()))->toUp()->getResult());
    }

    public function toLow () :self {
        $this->char = strtolower($this->char);
        return $this;
    }

    public function isLow () {
        return ($this->getResult() === (new self($this->getResult()))->toLow()->getResult());
    }

    public function getResult()
    {
        return $this->char;
    }

    public function __construct(string $char)
    {
        if (strlen($char) == 1) {
            $this->char = $char;
        }else {
            throw new \Exception("{$char} is not char" .strlen($char));
        }
    }
}