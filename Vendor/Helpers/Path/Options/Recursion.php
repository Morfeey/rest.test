<?php


namespace Helpers\Path;


final class Recursion
{
    public $isActive;

    public static function isOn () {
        return new self(true);
    }

    public static function isOff () {
        return new self(false);
    }

    private function __construct(bool $isActive = false)
    {
        $this->isActive = $isActive;
    }
}