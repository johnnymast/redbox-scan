<?php
require '../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', true);

/**
 * This file is temporary to the project it will go away and make way for
 * examples directory showing you the ropes and also shows how to make custom adaptors
 * for your needs.
 */
$path     = dirname(__FILE__)."/assets";
$tmpfile  = $path.'/new.tmp';
$timefile = $path.'/time.txt';
$datafile = $path.'/data.yml';

$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem($datafile));

/**
 * Lets index the assets folder.
 *
 */
$scan->index($path);

/**
 * Write a new tmp file so we can check if there where new or changed files found./
 */
file_put_contents($tmpfile, 'Hello world');

/**
 * Modify one file..
 */
file_put_contents($timefile, time());

/**
 * Lets see if the scanner picked it up.
 */
$report = $scan->scan();

file_put_contents($timefile, '');
unlink($tmpfile);

if(php_sapi_name() == "cli") {

    echo "New files\n\n";
    foreach ($report->getNewfiles() as $file) {
        echo $file->getFilename() . ' ' . $file->getMD5hash()."\n";
    }

    echo "\nModified Files\n\n";
    foreach ($report->getModifiedFiles() as $file) {
        echo $file->getFilename() . ' ' . $file->getMD5hash()."\n";
    }
    echo "\n";

} else {
    echo '<h1>New files</h1>';
    foreach ($report->getNewfiles() as $file) {
        echo '<li>' . $file->getFilename() . ' ' . $file->getMD5hash() . '</li>';
    }
    echo '</ul>';


    echo '<h1>Modified Files</h1>';
    foreach ($report->getModifiedFiles() as $file) {
        echo '<li>' . $file->getFilename() . ' ' . $file->getMD5hash() . '</li>';
    }
    echo '</ul>';
}




