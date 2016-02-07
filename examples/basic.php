<?php
require 'autoload.php';

/**
 * This example shows the basic usage of file filesystem it self to store scan information
 * about your scans. If you would use this code in real life please make sure you store the output file (data.yml)
 * in a secure location on your drive.
 */

$path     = dirname(__FILE__)."/assets";
$newfile  = $path.'/new.tmp';
$timefile = $path.'/time.txt';
$datafile = $path.'/data.yml';

/**
 * Oke lets instantiate a new service and scan the assets folder inside
 * our current folder and write the data.yml file to the filesystem using the Filesystem adapter.
 */
$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem($datafile));
$scan->index($path, 'Basic scan', date("Y-m-d H:i:s"));

/**
 * After indexing the directory let's create a new file and update an other so
 * we can see if the filesystem picks it up.
 */
file_put_contents($newfile, 'Hello world');
file_put_contents($timefile, time());

/**
 * Oke the changes have been made lets scan the assets directory again for changes.
 */
$report = $scan->scan();

/**
 * Do the cleanup. This is not needed if this where to be real code.
 */
unlink($newfile);

/**
 * Output the changes since index action.
 */
if(php_sapi_name() == "cli") {

    echo "New files\n\n";
    foreach ($report->getNewfiles() as $file) {
        echo $file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath())."\n";
    }

    echo "\nModified Files\n\n";
    foreach ($report->getModifiedFiles() as $file) {
        echo $file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath())."\n";
    }
    echo "\n";

} else {
    echo '<h1>New files</h1>';
    foreach ($report->getNewfiles() as $file) {
        echo '<li>'.$file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath()).'</li>';
    }
    echo '</ul>';

    echo '<h1>Modified Files</h1>';
    foreach ($report->getModifiedFiles() as $file) {
        echo '<li>'.$file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath()).'</li>';
    }
    echo '</ul>';
}
