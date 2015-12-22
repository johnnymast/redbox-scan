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
        if (getenv('IS_TRAVIS') == true) {
            $this->markTestSkipped('This test is only for testing');
        }
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
        if (getenv('IS_TRAVIS') == true) {
            $this->markTestSkipped('This test is only for testing');
        }
        $adapter = new Adapter\Ftp (
            $host = 'ftp.kernel.org',
            $username = 'ad',
            $password = 'anonymous',
            $file = ''
        );
        $adapter->authenticate();
        unset($adapter);
    }

    /**
     * Validate that read files on non existing files
     */
    public function test_ftp_should_return_false_if_read_fails() {
        if (getenv('IS_TRAVIS') == true) {
            $this->markTestSkipped('This test is only for testing');
        }
        $adapter = new Adapter\Ftp (
            $host     = 'ftp.kernel.org',
            $username = 'anonymous',
            $password = 'anonymous',
            $file = '/'
        );
        $adapter->authenticate();
        $this->assertFalse($adapter->read());

        unset($adapter);
    }
}
