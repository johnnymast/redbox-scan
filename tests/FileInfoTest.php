<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan;

/**
 * This class will run tests against the FileInfo class.
 *
 * @coversDefaultClass  Scan\Filesystem\FileInfo
 * @package Redbox\Scan\Tests
 */
class FileInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test that FileInfo::getFileHash() returns an empty string on failures.
     */
    function test_fileinfo_get_hash_should_return_empty()
    {
        $this->assertEquals(Scan\Filesystem\FileInfo::getFileHash(''), '');
        $this->assertEquals(Scan\Filesystem\FileInfo::getFileHash('/_i_29kl_don\tExist'), '');
    }

    /**
     * Test that FileInfo::getFileHash() returns a non empty string (hash) on
     * success.
     */
    function test_fileinfo_get_hash_should_return_hash() {
        $this->assertNotEmpty(Scan\Filesystem\FileInfo::getFileHash(__FILE__));
    }

}