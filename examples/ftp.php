<?php
require '../vendor/autoload.php';

/**
 * This example shows you how you could use the file transfer protocol (FTP) to store information about the filesystem
 * If you would use this code in real life please make sure you store the output file (data.yml). By using the FTP method
 * it gets a lot harder for hackers to modify your scan file and fake the the scan results.
 */

$path     = dirname(__FILE__)."/assets";
$tmpfile  = $path.'/new.tmp';
$timefile = $path.'/time.txt';

/**
 * Change the values below to match your ftp settings.
 */
$host     = "";
$username = "";
$password = "";
$datafile = "/httpdocs/data.yml";

/**
 * Create the FTP adapter.
 */
$adapter = new Redbox\Scan\Adapter\Ftp(
    $host,
    $username,
    $password,
    $datafile
);

try {

    /**
     * Since PSR-4 indicates the following:
     * Autoloader implementations MUST NOT throw exceptions, MUST NOT raise errors of any level, and SHOULD NOT return a value.
     *
     * We needed a seperate call to connect authenticate to the ftp server that would be able to report errors if needed.
     */
    if ($adapter->authenticate()) {

        /**
         * Oke lets instantiate a new service and scan the assets folder inside
         * our current folder and write the data.yml file to the filesystem using the Filesystem adapter.
         */
        $scan = new Redbox\Scan\ScanService($adapter);
        $scan->index($path, 'Basic scan', date("Y-m-d H:i:s"));

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
         * Do the cleanup. This is not needed if this where to be real code.
         */
        unlink($tmpfile);

        /**
         * Output the changes since the index action.
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
                echo '<li>' . $file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath()).'</li>';
            }
            echo '</ul>';

            echo '<h1>Modified Files</h1>';
            foreach ($report->getModifiedFiles() as $file) {
                echo '<li>'.$file->getFilename().' '.Redbox\Scan\Filesystem\FileInfo::getFileHash($file->getRealPath()).'</li>';
            }
            echo '</ul>';
        }
    }

} catch (Exception $e) {
   /* Handle Exception */
    if(php_sapi_name() != "cli") echo '<pre>';
    print_r($e->getMessage());
}