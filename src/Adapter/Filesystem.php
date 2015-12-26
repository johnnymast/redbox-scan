<?php
namespace Redbox\Scan\Adapter;
use Redbox\Scan\Report;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the most basic adaptor on earth. Just read and write
 * to somewhere on the filesystem.
 *
 * @package Redbox\Scan\Adapter
 */
class Filesystem implements AdapterInterface
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * Filesystem constructor.
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Read the previous scan results from the file system.
     * @return bool|Report\Report
     */
    public function read()
    {
        if (file_exists($this->filename) == true) {
            $stored_report  = Yaml::parse(@file_get_contents($this->filename));
            $report = Report\Report::fromArray($stored_report);
            return $report;
        }
        return false;
    }

    /**
     * Write the report to the filesystem so we can reuse it
     * at a later stace when we invoke Redbox\Scan\ScanService's scan() method.
     *
     * @param Report\Report|null $report
     * @return bool
     */
    public function write(Report\Report $report = null)
    {
        if ($report) {
            $data = $report->toArray();
            $data = Yaml::dump($data, 99);
            if (@file_put_contents($this->filename, $data) !== false) /* I hate the @ with a passion with if we dont do it the tests will fail */
                return true;

        }
        return false;
    }
}
