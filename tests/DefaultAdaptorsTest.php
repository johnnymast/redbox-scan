<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan;

class DefaultAdaptorsTest extends Assets\TestBase
{

    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     *
     * @coversDefaultClass       \Redbox\Scan\Adaptor\Filesystem
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

    // TODO: PHP 7 test Scan\Adapter\Filesystem() without argument
}