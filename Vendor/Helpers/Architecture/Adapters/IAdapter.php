<?php

namespace Helpers\Architecture\Adapters;

use Helpers\Architecture\Entity;

interface IAdapter
{
    public function where(string $field, $value, string $operator, bool $isAnd = true);
    public function select(string ...$fields);
    public function from(string $from = null);

    public function setEntities (Entity ...$entities);

    public function setEntityClass (string $className);
    public function save ();
    public function update();
    public function delete ();
    public function truncate();

    public function getByID (...$ids);

    public function getResult();

}