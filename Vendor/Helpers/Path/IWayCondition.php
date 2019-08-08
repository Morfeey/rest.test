<?php


namespace Helpers\Path;


interface IWayCondition
{
    public function remove ();
    public function create ();
    public function copy();
    public function move();
    public function rename();
}