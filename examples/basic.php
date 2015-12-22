<?php
require '../vendor/autoload.php';

/**
 * This file is temporary to the project it will go away and make way for
 * examples directory showing you the ropes and also shows how to make custom adaptors
 * for your needs.
 */
$path = "../";
$tmpfile  = $path.'/new.tmp';
$timefile = $path.'/time.txt';

$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem('assets/data.yml'));


/**
 * Lets index the assets folder.
 *
 */
$scan->index($path);


/**
 * Write a new tmp file so we can check if there where new or changed files found./
 */
file_put_contents($tmpfile,'Hello world');


/**
 * Modify one file..
 */
file_put_contents($timefile, time());


/**
 * Lets see if the scanner picked it up.
 */
$report = $scan->scan();

echo '<h1>New files</h1>';
foreach($report->getNewfiles() as $file) {
    echo '<li>'.$file->getFilename().' '.$file->getMD5hash().'</li>';
}
echo '</ul>';


echo '<h1>Modified Files</h1>';
foreach($report->getModifiedFiles() as $file) {
    echo '<li>'.$file->getFilename().' '.$file->getMD5hash().'</li>';
}
echo '</ul>';


file_put_contents($timefile, ''); /* Keep it empty */
unlink($tmpfile);




