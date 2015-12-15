<?php
namespace Redbox\Scan\Adapter;
use Redbox\Scan\Report;

interface AdapterInterface
{
    public function read();
    public function write(Report\Report $report = null);
}