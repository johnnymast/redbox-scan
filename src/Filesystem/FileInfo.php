<?php
namespace Redbox\Scan\Filesystem;

/**
 * Provide a hashing facility so we can compare files.
 *
 * @package Redbox\Scan\Filesystem
 */
class FileInfo
{
    /**
     * Return a hash of a given file.
     *
     * @param string $file
     * @return string
     */
    public static function getFileHash($file = "")
    {
        if (file_exists($file) === true) {
            return md5_file($file);
        }
        return '';
    }
}