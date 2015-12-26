<?php
namespace Redbox\Scan\Filesystem;


class FileInfo
{
    static function getFileHash($file = "")
    {
        if (file_exists($file) == true) {
            return md5_file($file);
        }
        return '';
    }
}