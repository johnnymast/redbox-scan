<?php
namespace Redbox\Scan\Filesystem;


class FileInfo
{
    static function getFileHash($file = "")
    {
        return md5_file($file);
    }
}