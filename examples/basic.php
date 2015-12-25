<?php
require '../vendor/autoload.php';

/**
 * This example shows the basic usage of file filesystem it self to store scan information
 * about your scans. If you would use this code in real life please make sure you store the output file (data.yml)
 * in a secure location on your drive.
 */

$path     = dirname(__FILE__)."/assets";
$tmpfile  = $path.'/new.tmp';
$timefile = $path.'/time.txt';
$datafile = $path.'/data.yml';

/**
 * Oke lets instantiate a new service and scan the assets folder inside
 * our current folder and write the data.yml file to the filesystem using the Filesystem adapter.
 */
$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem($datafile));
$scan->index($path);

/**
 * After indexing the directory let's create a new file and update an other so
 * we can see if the filesystem picks it up.
 */
file_put_contents($tmpfile, 'Hello world');
file_put_contents($timefile, time());

/**
 * Oke the changes have been made lets scan the assets directory again for changes.
 */
$report = $scan->scan();

/**
 * Do the cleanup.
 */
file_put_contents($timefile, '');
unlink($tmpfile);

/**
 * Output the changes since the scan.
 */
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
