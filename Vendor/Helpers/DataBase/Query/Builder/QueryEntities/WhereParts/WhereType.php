<?php


namespace Helpers\DataBase\Query\Builder\QueryEntities\WhereParts;


final class WhereType implements IWhereType
{
    protected $type;

    public function getType () {
        return $this->type;
    }

    public function isFirstCondition (): bool {
        return (is_null($this->getType()));
    }


    public static function firstCondition () {
        return new self();
    }

    public static function isAnd () {
        return new self('and');
    }

    public static function isOr () {
        return new self('or');
    }

    private function __construct(string $type = null)
    {
        if (!is_null($type)) {
            $type = strtoupper($type);
        }
        $this->type = $type;
    }
}