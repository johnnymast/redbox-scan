<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;
use Redbox\Scan;

class AuthenticationServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Return a scan service instance
     *
     * @return Scan\ScanService
     */
    private function getNewService(Scan\Adapter\AdapterInterface $adapter = NULL) {
        return new Scan\ScanService($adapter);
    }

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the index method.
     *
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adaptor must been set before calling index()
     * @coversDefaultClass       \Redbox\Scan\ScanService
     */
    public function test_index_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->index("/");
    }

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     *
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adaptor must been set before calling scan()
     * @coversDefaultClass       \Redbox\Scan\ScanService
     */
    public function test_scan_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->scan();
    }

    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     *
     * @coversDefaultClass       \Redbox\Scan\ScanService
     */
    public function test_service_scan_should_throw_exception_on_no_adaptor()
    {
        if (phpversion() < 7.0) {
            $this->setExpectedException('PHPUnit_Framework_Error');

        } elseif (phpversion() >= 7.0) {
            $this->setExpectedException('TypeError');
        }
        $service = $this->getNewService(new Assets\Adapter\WithoutInterface());
        $service->scan("/");
    }
}