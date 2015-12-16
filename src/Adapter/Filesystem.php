<?php
namespace Redbox\Scan\Adapter;
use Symfony\Component\Yaml\Yaml;
use Redbox\Scan\Report;

class Filesystem extends AbstractAdapter
{

    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function read() {
        $data = Yaml::parse(file_get_contents($this->filename));
        return $data;
    }

    // TODO: This should be an universial exception
    public function write(Report\Report $report = null) {
        if ($report) {
            $data = $report->toArray();
            $data = Yaml::dump($data, 99);
            file_put_contents($this->filename, $data);
            return true;
        }
        return false;
    }
}
