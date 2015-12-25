<?php
namespace Redbox\Scan\FileSystem;

/**
 * This is a surrogate class for \SplFileInfo and adds
 * the function getMD5Hash so we can hash files on the filesystem.
 *
 * @package Redbox\Scan\FileSystem
 */
class FileInfo extends \SplFileInfo
{
    public function __construct($filename)
    {
        parent::__construct($filename);
    }

    /**
     * Return the md5 hash of a file.
     *
     * @return string
     */
    public function getMD5Hash()
    {
        if ($this->isFile()) {
            return md5_file($this->getRealPath());
        }
        return '';
    }
}