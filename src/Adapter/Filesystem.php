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

    public function write(Report\Report $report = null) {
        $data = $report->toArray();
        $data = Yaml::dump($data,99);
        file_put_contents($this->filename, $data);
    }
}
