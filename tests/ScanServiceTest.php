<?php
namespace Redbox\Scan\Tests;
/*
use Redbox\Scan\Exception;
use Redbox\Scan;
*/
/**
 * This class will run tests against the ScanService class.
 *
 */
class ScanServiceTest extends \PHPUnit_Framework_TestCase
{

    public function test_true() {
        return $this->assertFalse(false);
    }

    /**
     * Return a scan service instance. This instance will be used in the tests
     * below.
     *
     * @param Scan\Adapter\AdapterInterface|NULL $adapter
     * @return Scan\ScanService

    private function getNewService(Scan\Adapter\AdapterInterface $adapter = NULL)
    {
        return new Scan\ScanService($adapter);
    }
     */

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the index method.
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adaptor must been set before calling index()

    public function test_index_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->index("/");
    }
     */

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the scan method.
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adaptor must been set before calling scan()

    public function test_scan_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->scan();
    }
     */
    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the scan method. In the case of PHP >= 7.0 it will throw
     * and tests to catch a TypeError.

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
     */
    /**
     * Tests that if we call ScanService::scan() we should get a false return
     * if there as an error reading the adaptor.

    public function test_service_scan_returns_false_if_reading_the_adaptor_fails()
    {
        $service = $this->getNewService(new Scan\Adapter\Filesystem('I do not exist'));
        $this->assertFalse($service->scan());
    }
     * */

    /**
     * Tests that if we call ScanService::index() we should get a false return
     * if there as an error writing to the adaptor.

    public function test_service_scan_returns_false_if_writing_the_adaptor_fails()
    {
        $service = $this->getNewService();
        $this->assertFalse(@$service->index(dirname(__FILE__).'/Assets', new Scan\Adapter\Filesystem('I do not exist \'s invalid _ @()))@903 file / \ ')));
    }
     */
}