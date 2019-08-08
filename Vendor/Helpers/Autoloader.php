<?php

namespace Helpers;

include_once "Autoloader/IAutoload.php";
include_once "Path/Directory.php";

use Helpers\Autoloader\IAutoload;
use Helpers\Path\Directory;
use Helpers\Path\Recursion;

class Autoloader implements IAutoload
{
    protected $filesBefore;
    protected $files;
    protected $filesAfter;

    public function addFilesAfter(string ...$files)
    {
        foreach ($files as $file) {
            $this->filesAfter[] = $file;
        }
        return $this;
    }

    public function addFilesBefore(string ...$files)
    {
        foreach ($files as $file) {
            $this->filesBefore[] = $file;
        }
        return $this;
    }

    /**
     * @param Recursion| Recursion::isOn $recursion
     * @param Directory ...$directories
     */
    public function addDirectories(Recursion $recursion = null, Directory ... $directories)
    {
        $recursion = (is_null($recursion)) ? Recursion::isOn() : $recursion;

        foreach ($directories as $directory) {
            $files = $directory->getFiles('*.php', $recursion);
            foreach ($files as $file) {
                $this->files[] = $file;
            }
        }
        return $this;
    }

    public function start()
    {
        $register = function (string ...$files) {
            spl_autoload_register(function () use ($files) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        include_once $file;
                    }
                }
            });
        };

        $register (... $this->filesBefore);
        $register (... $this->files);
        $register (... $this->filesAfter);
        return $this;
    }

    public function __construct()
    {
        $this->filesAfter = [];
        $this->filesBefore = [];
        $this->files = [];
    }
}