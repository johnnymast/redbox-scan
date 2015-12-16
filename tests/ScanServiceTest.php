<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Exception;

class AuthenticationServiceTest extends Assets\TestBase {

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the index method.
     *
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adaptor must been set before calling index()
     * @coversDefaultClass       \Redbox\Scan\ScanService
     */
    public function test_index_should_throw_runtime_exception() {
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
    public function test_scan_should_throw_runtime_exception() {
        $service = $this->getNewService();
        $service->scan();
    }

    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     *
     *
     * @expectedException        PHPUnit_Framework_Error1
     * @coversDefaultClass       \Redbox\Scan\ScanService
     */
    public function test_constructor_should_throw_exception_on_invalid_adaptor() {

        if (phpversion() < 7.0) {
            $this->setExpectedException('PHPUnit_Framework_Error');

        } elseif (phpversion() >= 7.0) {
            $this->setExpectedException('TypeError');
        }

        $service = $this->getNewService(new Assets\Adapter\WithoutAbstract());
        $service->scan("/");
    }

}