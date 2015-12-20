<?php
require '../vendor/autoload.php';

/**
 * This file is temporary to the project it will go away and make way for
 * examples directory showing you the ropes and also shows how to make custom adaptors
 * for your needs.
 */
$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem('assets/data.yml'));
$report = $scan->scan();
print_r($report->getModifiedFiles());




