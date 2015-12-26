<?php
namespace Redbox\Scan\FileSystem;


class FileInfo extends \SplFileInfo
{


    static function getFileHash($file = "")
    {
        return md5_file($file);
    }
}