<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan;

/**
 * This class will run tests against the Report class.
 *
 * @coversDefaultClass  \Redbox\Scan\Report\Report
 * @package Redbox\Scan\Tests
 */
class ReportTest extends \PHPUnit_Framework_TestCase
{

    /**
     * We need to validate that a RuntimeException if the input array for method
     * Report::fromArray() does not contain the required keys.
     *
     * @expectedException        \Redbox\Scan\Exception\RuntimeException
     */
    public function test_report_from_array_method_should_throw_exception_if_wrong_keys_are_missing()
    {
        $array = array(
            'some' => 'value',
        );
        Scan\Report\Report::fromArray($array);
    }

    /**
     * We need to validate that a RuntimeException if the input array for method
     * Report::fromArray() does not contain the required keys.
     */
    public function test_report_from_array_returns_a_valid_report()
    {
        $array = array(
            'name'  => 'Test scan',
            'date'  => date(DATE_RFC2822),
            'path'  => '/somepath',
            'items' => array(),
        );
        $report = Scan\Report\Report::fromArray($array);
        $this->assertInstanceOf('Redbox\Scan\Report\Report', $report);
    }

    /**
     * We need to test that toArray method on an instance of Redbox\Scan\Report\Report
     * returns a valid array.
     */
    public function test_report_to_array_returns_a_valid_array()
    {
        $input = array(
            'name'  => 'Test scan',
            'date'  => date(DATE_RFC2822),
            'path'  => '/somepath',
            'items' => array(),
        );
        $report = Scan\Report\Report::fromArray($input);
        $output = $report->toArray();

        $this->assertEquals(json_encode($input), json_encode($output));
    }
}