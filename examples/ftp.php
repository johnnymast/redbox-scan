<?php
require '../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', true);

/**
 * This file is temporary to the project it will go away and make way for
 * examples directory showing you the ropes and also shows how to make custom adaptors
 * for your needs.
 */
$path = dirname(__FILE__)."/assets";
$adapter = new Redbox\Scan\Adapter\Ftp('host', 'username', 'password', '/httpdocs/data.yml');

if ($adapter->authenticate()) {
    $scan = new Redbox\Scan\ScanService($adapter);
    $report = $scan->scan();

    print_r($report->getModifiedFiles());

} else {
    die("Could not authorize to the ftp server");
}
$scan->index($path);