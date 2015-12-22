<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan\Adapter;

/**
 *
 * @coversDefaultClass  \Redbox\Scan\Adaptor\Ftp
 * @package Redbox\Scan\Tests
 */
class FtpAdaptorTest extends Assets\TestBase
{
    /**
     * Validate that vailding connections should throw a Exception\RuntimeException
     * @expectedException \Redbox\Scan\Exception\RuntimeException
     */
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

    /**
     * Validate that vailding connections should throw a Exception\RuntimeException
     * @expectedException \Redbox\Scan\Exception\RuntimeException
     */
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
    }
}
