<?php


namespace Helpers\Routing;


class Headers
{
    /**
     * @var $headers HeaderItem[]
     */
    protected $headers;

    public function addHeader (string $key, string $value) {
        $this->headers[] = new HeaderItem($key, $value);
        return $this;
    }

    public function addHeaderItem (HeaderItem ... $items) {
        foreach ($items as $item) {
            $this->addHeader($item->getKey(), $item->getValue());
        }
        return $this;
    }

    public function send () {
        foreach ($this->headers as $header) {
            header("{$header->getKey()}: {$header->getValue()}");
        }
        return $this;
    }

    public function getAll () {
        return getallheaders();
    }

    public function __construct(HeaderItem ...$items)
    {
        $this->addHeaderItem(...$items);
    }

}