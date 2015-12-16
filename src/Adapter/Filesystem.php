<?php
namespace Redbox\Scan\Adapter;
use Symfony\Component\Yaml;
use Redbox\Scan\Report;



/**
 * This is the most basic adaptor on earth. Just read and write
 * to somewhere on the filesystem.
 *
 * @package Redbox\Scan\Adapter
 */
class Filesystem extends AbstractAdapter
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
     *
     * @return array
     */
    public function read() {
        $data = Yaml\Yaml::parse(file_get_contents($this->filename));
        return $data;
    }

    // TODO: This should be an universial exception
    /**
     * Write the report to the filesystem so we can reuse it
     * at a later stace when we invoke Redbox\Scan\ScanService's scan() method.
     *
     * @param Report\Report|null $report
     * @return bool
     */
    public function write(Report\Report $report = null) {
        if ($report) {
            $data = $report->toArray();
            $data = Yaml\Yaml::dump($data, 99);
            file_put_contents($this->filename, $data);
            return true;
        }
        return false;
    }
}
