<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan\Adapter;
use Redbox\Scan\Report;
use Redbox\Scan;

/**
 * This class will run tests against the default Ftp adapter. Please not that this
 * tests will be skipped on travis. You can only run these tests on your local machine.
 *
 * @coversDefaultClass  \Redbox\Scan\Adapter\Ftp
 * @package Redbox\Scan\Tests
 * @group ExcludeFromHHVM
 */
class FtpAdapterMiscTest extends \PHPUnit_Framework_TestCase
{

    /**
     * That that Adapter\Ftp::setPassiveMode() will return NULL if there
     * is no connection.
     */
    public function test_ftp_set_passive_mode_returns_null_if_not_connected()
    {
        $anonymous_ftp_adapter = new Adapter\Ftp (
            'ftp.kernel.org',
            'anonymous',
            'anonymous',
            ''
        );
        $this->assertNull($anonymous_ftp_adapter->setPassiveMode(true));
    }

    /**
     * That that Adapter\Ftp::setActiveMode() will return NULL if there
     * is no connection.
     */
    public function test_ftp_set_active_mode_returns_null_if_not_connected()
    {
        $anonymous_ftp_adapter = new Adapter\Ftp (
            'ftp.kernel.org',
            'anonymous',
            'anonymous',
            ''
        );
        $this->assertNull($anonymous_ftp_adapter->setActiveMode());
    }

    /**
     * Validate that failing connections throw a Exception\RuntimeException
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
     * Validate that invalid login's throw a Exception\RuntimeException
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

    /**
     * Test if Adapter\Ftp::setTransferMode() to FTP_MODE_ASCII will return
     * FTP_MODE_ASCII when Adapter\Ftp::getTransferMode() is called.
     */
    public function test_ftp_set_and_get_transfer_mode_ascii_returns_ascii()
    {
        $adapter = new Adapter\Ftp (
            $host = 'ftp.kernel.org',
            $username = 'ad',
            $password = 'anonymous',
            $file = ''
        );
        $adapter->setTransferMode(Adapter\Ftp::FTP_MODE_ASCII);
        $this->assertEquals(Adapter\Ftp::FTP_MODE_ASCII, $adapter->getTransferMode());
    }

    /**
     * Test if Adapter\Ftp::setTransferMode() to FTP_MODE_ASCII will return
     * FTP_MODE_ASCII when Adapter\Ftp::getTransferMode() is called.
     */
    public function test_ftp_set_and_get_transfer_mode_binary_returns_binary()
    {
        $adapter = new Adapter\Ftp (
            $host = 'ftp.kernel.org',
            $username = 'ad',
            $password = 'anonymous',
            $file = ''
        );
        $adapter->setTransferMode(Adapter\Ftp::FTP_MODE_BINARY);
        $this->assertEquals(Adapter\Ftp::FTP_MODE_BINARY, $adapter->getTransferMode());
    }

    /**
     * Assert that reading from a local ftp server will work if the credentials are correct.
     */
    public function test_ftp_local_connection_file_read()
    {
        if (($user = getenv('FTP_USER'))  && ($pass = getenv('FTP_PASSWORD')) && ($host = getenv('FTP_HOST')))
        {
            $ftp = new Adapter\Ftp (
                $host,
                $user,
                $pass,
                'httpdocs/scan.yml'
            );
            $ret = $ftp->authenticate();
            $this->assertTrue($ret);

            if ($ret === true)
            {
                $report = $ftp->read();
                $this->assertInstanceOf('Redbox\Scan\Report\Report', $report);
            }
            unset($ftp);
        }
    }

    /**
     * Assert that writing to a local ftp server will work if the credentials are correct.
     */
    public function test_ftp_local_connection_file_write()
    {
        if (($user = getenv('FTP_USER'))  && ($pass = getenv('FTP_PASSWORD')) && ($host = getenv('FTP_HOST')))
        {
            $ftp = new Adapter\Ftp (
                $host,
                $user,
                $pass,
                'httpdocs/scan_new.yml'
            );
            $ret = $ftp->authenticate();
            $this->assertTrue($ret);

            if ($ret === true)
            {
                $report = new Scan\Report\Report();
                $items  = array('a' => 'b');
                $report->setItems($items);

                $didWrite = $ftp->write($report);
                $this->assertTrue($didWrite);
            }
            unset($ftp);
        }
    }

    public function test_ftp_local_connection_file_write_fails_on_bad_report()
    {

        if (($user = getenv('FTP_USER'))  && ($pass = getenv('FTP_PASSWORD')) && ($host = getenv('FTP_HOST')))
        {
            $ftp = new Adapter\Ftp (
                $host,
                $user,
                $pass,
                'httpdocs/scan.yml'
            );

            $result = $ftp->read();
            $this->assertFalse($result);
            unset($ftp);
        }
    }

    /**
     * Assert that reading from a local ftp server will fail if the report is invalid.
     */
    public function test_ftp_local_connection_file_read_fails()
    {
        if (($user = getenv('FTP_USER'))  && ($pass = getenv('FTP_PASSWORD')) && ($host = getenv('FTP_HOST')))
        {
            $ftp = new Adapter\Ftp (
                $host,
                $user,
                $pass,
                'httpdocs/scan.yml'
            );
            $result = $ftp->write();
            $this->assertFalse($result);
        }
    }

    /**
     * Assert that writing to a local ftp server will fail if the user is not authenticated.
     */
    public function test_ftp_local_connection_file_write_fails()
    {
        if (($user = getenv('FTP_USER'))  && ($pass = getenv('FTP_PASSWORD')) && ($host = getenv('FTP_HOST')))
        {
            $ftp = new Adapter\Ftp (
                '',
                '',
                '',
                'httpdocs/scan_new.yml'
            );

            $report = new Scan\Report\Report();
            $items  = array('a' => 'b');
            $report->setItems($items);

            $result = $ftp->write($report);
            $this->assertFalse($result);
            unset($ftp);
        }
    }
}