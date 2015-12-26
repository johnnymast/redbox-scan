<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan;

/**
 * This class will run tests against the default Filesystem adapter.
 *
 * @coversDefaultClass  Scan\Adapter\Filesystem
 * @package Redbox\Scan\Tests
 */
class FilesystemAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     */
    public function test_filesystem_write_should_throw_exception_on_invalid_report_argument()
    {
        if (phpversion() < 7.0) {
            $this->setExpectedException('PHPUnit_Framework_Error');

        } elseif (phpversion() >= 7.0) {
            $this->setExpectedException('TypeError');
        }
        $filesystem = new Scan\Adapter\Filesystem('somefile.yml');
        $filesystem->write(new Assets\Report\InvalidReport());
    }

    /**
     * This test will make sure if no argument is passed (it defaults to null) it
     * will return false.
     */
    public function test_filesystem_write_fails_with_empty_argument()
    {
        $filesystem = new Scan\Adapter\Filesystem('somefile.yml');
        $this->assertFalse($filesystem->write());
    }

    /**
     * Test that Filesystem::read() returns false if a non existing source file
     * is passed to the adapter.
     */
    public function test_filesystem_read_fails_on_unknown_file()
    {
        $filesystem = new Scan\Adapter\Filesystem('I do not exist');
        $this->assertFalse($filesystem->read());
    }

    /**
     * Test that Yaml throws an ParseException if the yml file was corrupted.
     *
     * @expectedException \Symfony\Component\Yaml\Exception\ParseException
     */
    public function test_filesystem_read_files_that_could_not_be_parsed_throws_a_parse_exception()
    {
        $filesystem = new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/Data/Corrupt.yml');
        $filesystem->read();
    }
}
