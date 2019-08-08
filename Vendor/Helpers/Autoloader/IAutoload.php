<?php

namespace Helpers\Autoloader;

interface IAutoload {
    public function addFilesBefore (string ...$files);
    public function addFilesAfter (string ...$files);
    public function start();
}