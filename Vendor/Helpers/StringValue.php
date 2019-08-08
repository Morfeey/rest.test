<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.12.2018
 * Time: 23:08
 */

namespace Helpers;


class StringValue implements IInnerEssence
{
    private $result;

    public function replace (string $search, string $replace) : self {
        $this->result = str_replace($search, $replace, $this->result);
        return $this;
    }

    public function toUp () {
        $this->result = strtoupper($this->result);
        return $this;
    }

    public function toLow () {
        $this->result = strtolower($this->result);
        return $this;
    }

    public function firstCharUp () :self {
        $chars = $this->getChars();
        $listChars = [];
        foreach ($chars as $key => $char) {
            if ($key === 0) {
                $char->toUp();
            }
            $listChars[] = $char->getResult();
        }
        $result = implode('', $listChars);
        $this->result = $result;
        return $this;
    }

    public function firstCharLow () :self {
        $chars = $this->getChars();
        $listChars = [];
        foreach ($chars as $key => $char) {
            if ($key === 0) {
                $char->toLow();
            }
            $listChars[] = $char->getResult();
        }
        $result = implode('', $listChars);
        $this->result = $result;
        return $this;
    }

    public function explode (string $delimiter) {
        $result = explode($delimiter, $this->getResult());
        $result = array_diff($result, [''], [null]);
        return $result;
    }

    public function split (string ...$chars) {
        $getSlashedCharsString  = function () use ($chars) {
          $result = "";
          foreach ($chars as $char) {
              $result .= "\\$char";
          }
          return $result;
        };
        $pattern = "/[{$getSlashedCharsString()}]/";
        $result = preg_split($pattern, $this->getResult());
        return $result;
    }

    public function splitOnRegular ($pattern) {
        return preg_split($pattern, $this->getResult());
    }

    public function __construct(string $string)
    {
        $this->result = $string;
    }

    public function toCamelCase (bool $firstIsUp = true) {
        $split = $this->split('-', '_');
        if (count($split)>0) {
            $result = "";
            foreach ($split as $key => $item) {
                if (!$firstIsUp && $key === 0) {
                    $result .=
                        (new self($item))
                            ->getResult();
                }else {
                    $result .=
                        (new self($item))
                            ->firstCharUp()
                            ->getResult();
                }

            }
            $this->result = $result;
        }
        return $this;
    }

    public function toSnakeCase () {
        $this->toDelimiterCase('_');
        return $this;
    }

    public function toKebabCase () {
        $this->toDelimiterCase('-');
        return $this;
    }

    protected function toDelimiterCase (string $delimiter) :self {
        $chars = (new self($this->getResult()))->replace(' ', $delimiter)->toCamelCase()->getChars();
        $result = "";
        $temp = [];
        foreach ($chars as $char) {
            if ($char->isUp()) {
                $result .= "$delimiter{$char->toLow()->getResult()}";
            }else {
                $result .= $char->getResult();
            }
        }

        $result = ltrim($result, $delimiter);
        $this->result = $result;
        return $this;
    }

    /**
     * @return Char[]
     * @throws \Exception
     */
    public function getChars () {
        $result = [];
        $matches = [];
        preg_match_all("/./", $this->getResult(), $matches);
        foreach ($matches[0] as $item) {
            $result [] = new Char($item);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getResult() : string
    {
        return $this->result;
    }

    public function isContains (string $Item) :bool {
        $str = stristr($this->result, $Item);
        $result = (strlen($str) === 0 || !$str);
        return !$result;
    }

    public function isContained (string $inItem) :bool {
        $str = stristr($inItem, $this->result);
        $result = (strlen($str) === 0 || !$str);
        return !$result;
    }

}