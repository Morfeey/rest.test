<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:05
 */

namespace Helpers\Path;


use Helpers\StringValue;

class File extends PathHandler implements IWayCondition
{
    protected $SPLFileInfo;

    public function rename()
    {
        // TODO: Implement rename() method.
    }

    public function remove()
    {
        $unlink = unlink($this->getResult());
        if (!$unlink) {
            throw new \Exception("Failure delete file {$this->getResult()}");
        }
        return $this;
    }

    public function move()
    {
        // TODO: Implement move() method.
    }

    public function create()
    {
        touch($this->getResult());
        return $this;
    }

    public function rewriteContent (string $content) {
        $result = file_put_contents($this->getResult(), $content, LOCK_EX);
        if (!$result) {
            throw new \Exception("Error writing content in file: {$this->getResult()}");
        }
        return $this;
    }

    public function addContent (string $content) {
        $result = file_put_contents($this->getResult(), $content, FILE_APPEND | LOCK_EX);
        if (!$result) {
            throw new \Exception("Error adding content in file: {$this->getResult()}");
        }
        return $this;
    }

    public function getContent () {
        if ($this->isExists()) {
            return file_get_contents($this->getResult());
        }else {
            throw new \Exception("File ({$this->getResult()}) not found");
        }
    }

    public function getLastLevelPath(): string
    {
        return $this->ways[count($this->ways)-2];
    }

    public function getDirectory_old(): string
    {
        return $this->SPLFileInfo->getPath();
    }

    public function getDirectory () {
        $result = "";
        foreach ($this->ways as $item) {
            $result .= self::getLastSlashCustom($item);
            if ($item === $this->getLastLevelPath()) {
                break;
            }
        }
        $result = self::deleteLastSlashCustom($result);
        return $result;
    }

    public function getExtension()
    {
        return $this->SPLFileInfo->getExtension();
    }

    public function getName()
    {
        return $this->SPLFileInfo->getBasename();
    }

    public function getNameWithoutExtension()
    {
        return (new StringValue($this->getName()))
            ->replace
            (
                "." . $this->getExtension(),
                ""
            )
            ->getResult();
    }

    public function copy(Directory $newDirectory = null, $name = null): File
    {
        if ($this->isExists()) {
            $PathHandler = (new self());

            if (!is_null($newDirectory)) {
                $PathHandler->addChild($newDirectory->getResult());
                if (!is_null($name) && $name!=="") {
                    $PathHandler->addChild($name);
                }else {
                    $PathHandler->addChild($this->getName());
                }
            }else {
                $PathHandler->addChild($this->getResult());
            }
            $newName = (new Name($PathHandler->getResult()))->getNewAbsolute()->getResult();
            if (!is_dir($PathHandler->getDirectory())) {
                mkdir($PathHandler->getDirectory(),0777, true);
            }
            $copy = copy($this->getResult(), $newName);
            $newFile = new self($newName);
            if ($copy && $newFile->isExists()) {
                return new self($newName);
            }else {
                throw new \Exception("Error copy file on new name: {$newFile->getResult()}");
            }

        }else {
            throw new \Exception("File: {$this->getResult()} not found");
        }
    }

    public function __construct(string ...$ways)
    {
        parent::__construct(...$ways);
        $this->subLastSlash();
        $this->SPLFileInfo = new \SplFileInfo($this->getResult());
    }
}