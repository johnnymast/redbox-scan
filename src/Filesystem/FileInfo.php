<?php
namespace Redbox\Scan\FileSystem;


class FileInfo extends \SplFileInfo {

    public function __construct($filename) {
        parent::__construct($filename);
    }

    public function getMD5Hash() {
        if ($this->isFile()) {
            return md5_file($this->getRealPath());
        }
        return '';
    }
}