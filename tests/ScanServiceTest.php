<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan;

/**
 * This class will run tests against the ScanService class.
 *
 * @coversDefaultClass  \Redbox\Scan\ScanService
 * @package Redbox\Scan\Tests
 */
class ScanServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Return a scan service instance. This instance will be used in the tests
     * below.
     *
     * @param Scan\Adapter\AdapterInterface|NULL $adapter
     * @return Scan\ScanService
     */
    private function getNewService(Scan\Adapter\AdapterInterface $adapter = NULL)
    {
        return new Scan\ScanService($adapter);
    }

    /**
     * Test if the Adapter set by the constructor gets set properly. We can do this by calling
     * ScanService::getAdapter().
     */
    public function test_construct_should_set_the_adapter_correct ()
    {
        $adapter = new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/tmp/scan.yml');
        $service = $this->getNewService($adapter);
        $this->assertEquals($adapter, $service->getAdapter());
    }

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the index method.
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adapter must been set before calling index()
     */
    public function test_index_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->index("/", 'Basic scan', date("Y-m-d H:i:s"));
    }

    /**
     * This test will make sure that an RuntimeException is thrown if there was no Adapter
     * set via either the constructor or via the ScanService::scan() method.
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     * @expectedExceptionMessage An Adapter must been set before calling scan()
     */
    public function test_scan_should_throw_runtime_exception()
    {
        $service = $this->getNewService();
        $service->scan();
    }

    /**
     * This test will make sure that an PHPUnit_Framework_Error is thrown if there was no Adapter
     * set via either the constructor or via the ScanService::scan() method.
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_service_scan_should_throw_exception_on_no_adapter()
    {
        $service = $this->getNewService(new Assets\Adapter\WithoutInterface());
        $service->scan("/");
    }

    /**
     * Tests that if we call ScanService::scan() we should get a false return
     * if there as an error reading the adapter.
     */
    public function test_service_scan_returns_false_if_reading_the_adapter_fails()
    {
        $service = $this->getNewService(new Scan\Adapter\Filesystem('I do not exist'));
        $this->assertFalse($service->scan());
    }

    /**
     * If all goes well ScanService::index() should return the report that was generated.
     */
    public function test_service_index_returns_a_report()
    {
        $service = $this->getNewService(new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/tmp/scan.yml'));
        $report = $service->index(dirname(__FILE__), 'Basic scan', date("Y-m-d H:i:s"));
        $this->assertInstanceOf('Redbox\Scan\Report\Report', $report);
    }

    /**
     * Test that if an adapter fails to write its report ScanService::index() returns false instead of
     * a report.
     */
    public function test_service_index_returns_false_on_failing_adapter()
    {
        $service = $this->getNewService(new Scan\Adapter\Filesystem('/i_cant_be_written_to.yml'));
        $return_value = $service->index(dirname(__FILE__), 'Basic scan', date("Y-m-d H:i:s"));
        $this->assertFalse($return_value);
    }

    /**
     * Test that ScanService::scan() returns a report if all goes well.
     */
    public function test_service_scan_returns_a_report()
    {
        $service = $this->getNewService(new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/tmp/scan.yml'));
        $report = $service->scan();
        $this->assertInstanceOf('Redbox\Scan\Report\Report', $report);
    }

    /**
     * Tests that if we call ScanService::index() we should get a false return
     * if there as an error writing to the adapter.
     */
    public function test_service_scan_returns_false_if_writing_the_adapter_fails()
    {
        $service = $this->getNewService();
        $this->assertFalse(@$service->index(dirname(__FILE__).'/Assets', 'Basic scan', date("Y-m-d H:i:s"), new Scan\Adapter\Filesystem('I do not exist \'s invalid _ @()))@903 file / \ ')));
    }

    /**
     * Tests that the scan routine will pickup on new files.
     */
    public function test_service_scan_will_detect_newfiles()
    {
        $filesystem = new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/tmp/scan.yml');
        $service = $this->getNewService($filesystem);

        $service->index(dirname(__FILE__).'/Assets/tmp/');


        file_put_contents(dirname(__FILE__).'/Assets/tmp/new.txt', time());

        $report = $service->scan();
        $this->assertTrue(count($report->getNewfiles()) > 0);

        /* Unlink the tmp file */
        unlink(dirname(__FILE__).'/Assets/tmp/new.txt');
    }

    /**
     * Tests that the scan routine will pickup on file changes.
     */
    public function test_service_scan_will_detect_modified_files()
    {
        $filesystem = new Scan\Adapter\Filesystem(dirname(__FILE__).'/Assets/tmp/scan.yml');
        $service = $this->getNewService($filesystem);

        file_put_contents(dirname(__FILE__).'/Assets/tmp/tmp.txt', '');

        $service->index(dirname(__FILE__).'/Assets/tmp/');


        file_put_contents(dirname(__FILE__).'/Assets/tmp/tmp.txt', time());

        $report = $service->scan();
        $this->assertTrue(count($report->getModifiedFiles()) > 0);

        /* Unlink the tmp file */
        unlink(dirname(__FILE__).'/Assets/tmp/tmp.txt');
    }
}