<?php
namespace Redbox\Scan\Adapter;
use Redbox\Scan\Report;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * This function should read previous reports from
     * the file system so we can compare it to the results of
     * Redbox\Scan\ScanService its scan() method.
     *
     * @return mixed
     */
    public function read();

    /**
     * This function should write a report to what ever the Adaptor is good for
     * (http|filesystem|ftp|etc ..)
     *
     * @param Report\Report|null $report
     * @return mixed
     */
    public function write(Report\Report $report = null);
}