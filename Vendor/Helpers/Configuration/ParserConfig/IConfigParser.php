<?php

namespace Helpers\Configuration\ParserConfig;

use Helpers\Configuration\FileConfig;

interface IConfigParser {
    public function toArray():array;
    public function setFileConfig(FileConfig $item);
}