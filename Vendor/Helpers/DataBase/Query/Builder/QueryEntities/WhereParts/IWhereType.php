<?php


namespace Helpers\DataBase\Query\Builder\QueryEntities\WhereParts;


interface IWhereType
{
    public function getType ();

    public function isFirstCondition (): bool;
}