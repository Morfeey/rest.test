<?php


namespace Helpers\Path;


interface IWay
{
    public function subLastSlash ();
    public function addLastSlash ();

    public function addFirstSlash();
    public function subFirstSlash();

    public function getFirstLevelPath() :string;
    public function getLastLevelPath() :string;
}