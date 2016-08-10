<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Adapter;
use Redbox\Scan;

/**
 * This class will run tests against the default Ftp adapter. Please not that this
 * tests will be skipped on travis. You can only run these tests on your local machine.
 *
 * @coversDefaultClass  \Redbox\Scan\Adapter\Ftp
 * @package Redbox\Scan\Tests
 * @group ExcludeFromHHVM
 */
class FtpAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test that ftp.kernel.org allows anonymous login's
     */
    public function test_ftp_authenticate_returns_true()
    {
        $anonymous_ftp_adapter = new Adapter\Ftp (
            'ftp.kernel.org',
            'anonymous',
            'anonymous',
            ''
        );

        $ret = $anonymous_ftp_adapter->authenticate();
        $this->assertTrue($ret);

        if ($ret === true)
        {
            $this->assertTrue($anonymous_ftp_adapter->setPassiveMode(true));
            $this->assertTrue($anonymous_ftp_adapter->setActiveMode());
        }
    }
}