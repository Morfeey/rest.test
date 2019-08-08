<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:06
 */

namespace Helpers\Path;


interface IPathOption
{
    public static function recurse ();
    public static function onlyHere ();
}