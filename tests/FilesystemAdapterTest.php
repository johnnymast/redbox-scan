<?php
namespace Redbox\Scan\Tests;
use Symfony\Component\Yaml\Yaml as Yaml;
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

    /**
     * Test that Filesystem::read returns the correct information.
     */
    public function test_filesystem_read_returns_correct_data()
    {
        $data_file = dirname(__FILE__).'/Assets/Filesystem/data.yml';
        $local_data = Yaml::parse(@file_get_contents($data_file));

        /**
         * Create new filesystem adapter and read the same file.
         * Then we compare the report to the $local_data array.
         */
        $filesystem = new Scan\Adapter\Filesystem($data_file);
        $report = $filesystem->read();

        /**
         * Compare the results
         */
        $this->assertEquals($local_data['name'], $report->getName());
        $this->assertEquals($local_data['path'], $report->getPath());
        $this->assertEquals($local_data['date'], $report->getDate());
        $this->assertEquals(array(), $report->getModifiedFiles());
        $this->assertEquals(array(), $report->getNewfiles());
    }

    /**
     * Compare a write and read operation on the filesystem adapter.
     * Lets hope this passes.
     */
    public function test_filesystem_write_and_read_get_the_same_data()
    {
        $src_file    = dirname(__FILE__).'/Assets/Filesystem/data.yml';
        $target_file = dirname(__FILE__).'/Assets/tmp/filesystem.yml';
        $local_data  = Yaml::parse(@file_get_contents($src_file));

        /**
         * Read the source file and create a report from it.
         * We will write te file to a temp location and then read it
         * and compare the results.
         */
        $fs1     = new Scan\Adapter\Filesystem($target_file);
        $report1 = Scan\Report\Report::fromArray($local_data);
        $fs1->write($report1);

        /**
         * Read the test file and compare the results.
         */
        $fs2 = new Scan\Adapter\Filesystem($target_file);
        $report2 = $fs2->read();

        /**
         * Here go comparing the 2 results.
         */
        $this->assertEquals($report2->getName(), $report1->getName());
        $this->assertEquals($report2->getPath(), $report1->getPath());
        $this->assertEquals($report2->getDate(), $report1->getDate());
        $this->assertEquals(array(), $report1->getModifiedFiles());
        $this->assertEquals(array(), $report1->getNewfiles());

        unset($fs1);
        unset($fs2);
        unset($report1);
        unset($report2);
        unlink($target_file);
    }

}
