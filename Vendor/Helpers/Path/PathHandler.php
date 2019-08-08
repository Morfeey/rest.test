<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.01.2019
 * Time: 11:14
 */

namespace Helpers\Path;

use Helpers\IInnerEssence;

include_once __DIR__ . "/../IInnerEssence.php";
include_once "HelperPath.php";
include_once "IWay.php";


class PathHandler implements IWay, IInnerEssence
{
    use HelperPath;

    protected $ways;
    protected $resultWay;

    public function addFirstSlash()
    {
        $this->resultWay = self::getFirstSlashCustom($this->resultWay);
        return $this;
    }

    public function subFirstSlash()
    {
        $this->resultWay = self::deleteFirstSlashCustom($this->resultWay);
        return $this;
    }

    public function getFirstLevelPath() :string
    {
        $result = (isset($this->ways[0])) ? $this->ways[0] : "";
        return $result;
    }

    public function getLastLevelPath() :string
    {
        $result = "";
        $count = count($this->ways);
        if ($count!==0) {
            $last = $count-1;
            $result = $this->ways[$last];
        }
        return $result;
    }

    public function subLastSlash()
    {
        $this->resultWay = self::deleteLastSlashCustom($this->resultWay);
        return $this;
    }

    public function addLastSlash()
    {
        $this->resultWay = self::getLastSlashCustom($this->resultWay);
        return $this;
    }

    public function addChild(string ...$ways)
    {
        foreach ($ways as $item) {
            $this->ways[] = $item;
        }
        $this->build();
        return $this;
    }

    public function subtract(string $way)
    {
        $ways = $this->ways;
        if (in_array($way, $ways)) {
            $key = array_search($way, $ways);
            unset($ways[$key]);
        }
        $this->ways = $ways;
        $this->build();
        return $this;
    }

    protected function build()
    {
        $result = "";
        foreach ($this->ways as $way) {
            $result .= self::getLastSlashCustom($way);
        }

        $result = self::deleteLastSlashCustom($result);

        $exp = explode("/", $result);
        $exp = array_diff($exp, ['']);
        $this->ways = $exp;
        $this->resultWay = $result;
        return $this;
    }

    public function getResult(): string
    {
        return $this->resultWay;
    }

    public function isExists(): bool
    {
        $way = $this->getResult();
        $result = (is_dir($way) || is_file($way));
        return $result;
    }

    public function __construct(string ...$ways)
    {
        $this->ways = [];
        $this->addChild(...$ways);
    }

}