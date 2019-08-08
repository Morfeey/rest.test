<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.01.2019
 * Time: 11:52
 */

namespace Helpers\Path;


trait HelperPath
{
    public static function getDocumentRoot () {
        return $_SERVER["DOCUMENT_ROOT"];
    }
    public  static function getLastSlashCustom(string $directory)
    {
        $directory = self::deleteLastSlashCustom($directory);
        $result = $directory . "/";
        return $result;
    }
    public static function deleteLastSlashCustom(string $directory)
    {
        $directory = str_replace("\\", "/", $directory);
        $result = rtrim($directory, "/");
        return $result;
    }

    public static function deleteFirstSlashCustom (string $directory) {
        $directory = str_replace('\\', '/', $directory);
        $result = ltrim($directory, '/');
        return $result;
    }
    
    public static function getFirstSlashCustom (string $directory) {
        $directory = self::deleteFirstSlashCustom($directory);
        $result = "/" . $directory;
        return $result;
    }
}