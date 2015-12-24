<?php
namespace Redbox\Scan\FileSystem;

/**
 * This is a surrogate class for \RecursiveDirectoryIterator and sets
 * the info class to \Redbox\Scan\FileSystem\FileInfo so we can extend the
 * functionality of the iterator with a md5 hash.
 *
 * @package Redbox\Scan\FileSystem
 */
class RecursiveDirectoryIterator extends \RecursiveDirectoryIterator
{
    /**
     * RecursiveDirectoryIterator constructor.
     * @param string $path
     */
    public function __construct($path = "")
    {
        $this->setInfoClass('\Redbox\Scan\Filesystem\FileInfo');
        parent::__construct($path, 0);

    }
}