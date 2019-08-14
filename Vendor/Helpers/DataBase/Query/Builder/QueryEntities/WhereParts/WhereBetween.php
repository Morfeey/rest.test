<?php


namespace Helpers\DataBase\Query\Builder\QueryEntities\WhereParts;


final class WhereBetween
{
    protected $start;
    protected $finish;
    protected $isNotBetween;

    public function isNot(): bool {
        return $this->isNotBetween;
    }

    public function getStart () {
        return $this->start;
    }

    public function getFinish () {
        return $this->finish;
    }

    public static function notBetween ($start, $finish) {
        return new self($start, $finish, true);
    }

    public static function between ($start, $finish) {
        return new self($start, $finish, false);
    }

    private function __construct($start, $finish, $isNotBetween = false)
    {
        $this->start = $start;
        $this->finish = $finish;
        $this->isNotBetween = $isNotBetween;
    }
}