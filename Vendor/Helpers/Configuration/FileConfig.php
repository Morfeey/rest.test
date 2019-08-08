<?php


namespace Helpers\Configuration;


use Helpers\Configuration\ParserConfig\DefaultParser;
use Helpers\Configuration\ParserConfig\IConfigParser;
use Helpers\Path\PathHandler;

class FileConfig
{
    protected $file;
    protected $extension;
    protected $directory;
    /**
     * @var $parser IConfigParser
    */
    protected $parser;


    /**
     * @param string $directory
     * @return self
     */
    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @param mixed $extension
     * @return self
     */
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @param string $file
     * @return self
     */
    public function setFile(string $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function setParser (IConfigParser $item = null) {
        $this->parser = (!is_null($item)) ? $item : (new DefaultParser($this));
        return $this;
    }

    public function getFile ():string {
        return (new PathHandler($this->directory, $this->file . "." . $this->extension))->subLastSlash()->getResult();
    }

    public function getParser (): IConfigParser {
        $this->parser->setFileConfig($this);
        return $this->parser;
    }

    public function __construct()
    {
        $this->setParser();
    }
}