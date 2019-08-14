<?php


namespace Helpers\DataBase\Query\Builder;


interface SQLBuilder
{
    public function getSQL (): string;
}