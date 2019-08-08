<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:05
 */

namespace Helpers\Path;

use Helpers\IInnerEssence;

include_once __DIR__ . "/../IInnerEssence.php";
include_once "Options/IPathOption.php";
include_once "Options/Recursion.php";
include_once "PathHandler.php";
include_once "IWayCondition.php";

class Directory extends PathHandler implements IWayCondition
{
    /**
     * Search Directories  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param Recursion|null $recursion |default Recursion::isOff()
     * @return array
     */
    public function getDirectories(string $Pattern = "*", Recursion $recursion = null): array
    {
        $recursion = (is_null($recursion)) ? Recursion::isOff() : $recursion;
        $GetDirectories = function (string $Directory) use (&$GetDirectories, $Pattern, $recursion) {
            $result = [];
            $Directory = self::getLastSlashCustom($Directory);
            $List = glob($Directory . $Pattern, GLOB_BRACE | GLOB_MARK | GLOB_ONLYDIR);
            foreach ($List as $Item) {
                $Item = self::deleteLastSlashCustom($Item);
                array_push($result, $Item);
                $Temp = $GetDirectories($Item);
                if ($recursion->isActive && count($Temp) != 0) {
                    foreach ($Temp as $item) {
                        $result[] = $item;
                    }
                }
            }
            return $result;
        };
        $result = $GetDirectories($this->resultWay);
        return $result;
    }

    /**
     * Search Files  (opening {a,b,c}, for search on 'a'|'b'|'c')
     * @param string|* $Pattern
     * @param Recursion|Recursion $recursion
     * @return array
     */
    public function getFiles(string $Pattern = "*", Recursion $recursion = null): array
    {
        $recursion = (is_null($recursion)) ? Recursion::isOff() : $recursion;
        $GetFiles = function (string $Directory) use (&$GetFiles, $Pattern, $recursion) {
            $result = [];
            $Dir = self::getLastSlashCustom($Directory);
            $Directories = glob("$Dir*", GLOB_ONLYDIR | GLOB_MARK);
            $Files = function () use ($Dir, $Pattern) {
                $result = [];
                $files = glob($Dir . $Pattern, GLOB_BRACE);
                foreach ($files as $Item) {
                    if (is_file($Item)) {
                        $result[] = $Item;
                    }
                }
                return $result;
            };
            $result = array_merge($result, $Files());
            if ($recursion->isActive) {
                foreach ($Directories as $Item) {
                    $result = array_merge($result, $GetFiles($Item));
                }
            }
            return $result;
        };
        $result = $GetFiles($this->resultWay);
        return $result;
    }

    public function create ($mode = 0777, Recursion $option = null) {
        $option = (is_null($option)) ? Recursion::isOn() : $option;
        $result = mkdir($this->getResult(), $mode, $option->isActive);
        if (!$result) {
            throw new \Exception("Failure create directory: {$this->getResult()}");
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function remove () {

        $files = $this->getFiles("*", Recursion::isOn());
        $directories = $this->getDirectories("*", Recursion::isOn());
        $directories = array_reverse($directories);

        foreach ($files as $file) {
            (new File($file))->remove();
        }
        foreach ($directories as $directory) {
            (new self($directory))->remove();
        }

        $delete = rmdir($this->getResult());
        if (!$delete) {
            throw new \Exception("Failure delete directory: {$this->getResult()}");
        }

        return $this;
    }

    public function move()
    {
        // TODO: Implement move() method.
    }

    public function copy()
    {
        // TODO: Implement copy() method.
    }

    public function rename()
    {
        // TODO: Implement rename() method.
    }

    /**
     * Up level directory
     * @return self
     */
    public function parent(): self
    {
        $last = array_key_last($this->ways);
        unset($this->ways[$last]);
        $this->build();
        return $this;
    }

    public function __construct(string ...$ways)
    {
        parent::__construct(...$ways);
        if (count($ways)===0) {
            $this->addChild(self::getDocumentRoot());
        }
    }
}