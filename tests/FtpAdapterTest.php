<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan\Adapter;
use Redbox\Scan;

/**
 * This clas will run tests against the default Ftp adapter. Please not that this
 * tests will be skipped on travis. You can only run these tests on your local machine.
 *
 */
class FtpAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validate that vailding connections should throw a Exception\RuntimeException
     * @expectedException \Redbox\Scan\Exception\RuntimeException

    public function test_ftp_failing_connections_should_throw_a_exception()
    {

        $adapter = new Adapter\Ftp (
            $host = 'example._com',
            $username = '',
            $password = '',
            $file = ''
        );
        $adapter->authenticate();
    }
     */
    /**
     * Validate that vailding connections should throw a Exception\RuntimeException
     * @expectedException \Redbox\Scan\Exception\RuntimeException

    public function test_ftp_invalid_authentication_should_throw_a_exception()
    {

        $adapter = new Adapter\Ftp (
            $host = 'ftp.kernel.org',
            $username = 'ad',
            $password = 'anonymous',
            $file = ''
        );
        $adapter->authenticate();
        unset($adapter);
    }*/
}