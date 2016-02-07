<?php
namespace Redbox\Scan\Tests;
use Redbox\Scan\Report;
use Redbox\Scan;

/**
 * This class will run tests against the default Filesystem adapter.
 *
 * @coversDefaultClass  Scan\Adapter\Filesystem
 * @package Redbox\Scan\Tests
 */
class AbstractReportTest extends \PHPUnit_Framework_TestCase
{
    public function test_set_and_get_items_return_the_same() {
        $report = new Report\Report();
        $items  = array('a' => 'b');
        $report->setItems($items);
        $this->assertEquals($items, $report->getItems());
    }

    public function test_set_and_get_newfiles_return_the_same() {
        $report = new Report\Report();
        $items  = array('a' => 'b');
        $report->setNewfiles($items);
        $this->assertEquals($items, $report->getNewfiles());
    }

    public function test_set_and_get_modified_files_return_the_same() {
        $report = new Report\Report();
        $items  = array('a' => 'b');
        $report->setModifiedFiles($items);
        $this->assertEquals($items, $report->getModifiedFiles());
    }
}