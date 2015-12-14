<?php
namespace Redbox\Scan\Resource;

class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator {
    public function __construct($path="")
    {
        parent::__construct($path, 0);
        $this->setInfoClass('\Redbox\Scan\Resource\FileInfo');
    }
}