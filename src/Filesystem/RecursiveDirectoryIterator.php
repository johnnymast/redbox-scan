<?php
namespace Redbox\Scan\FileSystem;

class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator {
    public function __construct($path="")
    {
        parent::__construct($path, 0);
        $this->setInfoClass('\Redbox\Scan\FileSystem\FileInfo');
    }
}