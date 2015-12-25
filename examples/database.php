<?php
require '../vendor/autoload.php';
use Redbox\Scan\Adapter;
use Redbox\Scan\Report\Report;

/**
 * First of all don't get intimidated by the number of lines in this example its mostly the database that
 * takes the numbers. In this example i will show you how to write a basic custom adapter using
 * read() and write() functions from the interface. This example will read and write it's date from a database so it can
 * be processed in index() and scan() on the ScanService.
 *
 * PS: I i do recommend you running this from a browser.
 *
 * Step 1.
 *  - Import assest/data.sql
 *
 * Step 2.
 *  - Change the database access settings
 *
 * Step 3.
 * = Run the code
 */

/**
 * Let me exampling about this database class. This class is not production ready its just
 * a really simple wrapper around a sample database.
 *
 * Class Database
 */
class Database extends \mysqli implements Adapter\AdapterInterface
{
    CONST SCAN_ID = 1;

    public function __construct($host, $user, $pass, $db)
    {
        parent::__construct();
        parent::connect($host, $user, $pass, $db);
    }

    /**
     * This class uses just one main scan record all the time. This is ID 1 (SCAN_ID) and contains the main information
     * about our scan name/date etc..
     *
     * @return array|bool
     */
    private function getScan()
    {
        $sql = sprintf("SELECT *, scandate as `date` FROM `scan` WHERE `id`='%s'", self::SCAN_ID);
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

    /**
     * Return the file items that have been stored prior to a scan action. These results are previously saved
     * via the write write() method.
     *
     * @return array
     */
    private function getReportItems()
    {
        $items = array();
        $sql = sprintf("SELECT * FROM `scanitems` WHERE `scanid`='%s'", self::SCAN_ID);
        $result = $this->query($sql);
        if ($result) {
            while ($item = $result->fetch_object()) {
                if (isset($items[$item->itemfolder]) === false)
                    $items[$item->itemfolder] = array();

                $items[$item->itemfolder][$item->itemname] = $item->md5hash;
            }
        }
        return $items;
    }

    /**
     * Read the previous scan results from the file system.
     *
     * @return array
     */
    public function read() {
        $report = new Report($this->getScan());
        $report->setItems($this->getReportItems());
        return $report;
    }

    /**
     * Write the report to the filesystem so we can reuse it
     * at a later stace when we invoke Redbox\Scan\ScanService's scan() method.
     *
     * @param Report|null $report
     * @return bool
     */
    public function write(Report $report = null) {

        if ($report) {
            $scandata = array(
                'name' => $report->getName(),
                'path' => $report->getPath(),
            );

            /* Step 1. Update the scan. */
            $sql = sprintf("UPDATE `scan` SET `name`='%s', `path`='%s', `scandate`=NOW()", $this->real_escape_string($scandata['name']), $this->real_escape_string($scandata['path']));
            $this->query($sql);

            if ($this->affected_rows > 0) {
                $items = $report->getItems();
                if (count($items) > 0) {

                    /* Step 2. Delete old items */
                    $sql = sprintf("DELETE FROM `scanitems` WHERE `scanid`='%s'", self::SCAN_ID);
                    $this->query($sql);


                    /* Step 3. Insert the new items */
                    foreach($items as $path => $item) {
                        foreach ($item as $filename => $md5hash) {
                            $sql = sprintf("INSERT INTO `scanitems` SET `scanid`='%s', `itemfolder`='%s', `itemname`='%s', `md5hash`='%s'",
                                self::SCAN_ID,
                                $this->real_escape_string($path),
                                $this->real_escape_string($filename),
                                $this->real_escape_string($md5hash)

                            );
                           $this->query($sql);
                        }
                    }
                }
            }
            return false;
        }
        return false;
    }

}

if (class_exists('mysqli')) {

    try {

        $path = dirname(__FILE__)."/assets";
        $tmpfile  = $path.'/new.tmp';
        $timefile = $path.'/time.txt';

        $databaseAdaptor = new Database(
            "localhost",
            "root",
            "root",
            "scan"
        );

        /**
         * Oke lets instantiate a new service and scan the assets folder inside
         * our current folder and write the data.yml file to the filesystem using the Filesystem adapter.
         */
        $scan = new Redbox\Scan\ScanService($databaseAdaptor);
        $scan->index($path);

        /**
         * After indexing the directory let's create a new file and update an other so
         * we can see if the filesystem picks it up.
         */
        file_put_contents($tmpfile,'Hello world');
        file_put_contents($timefile, time());


        /**
         * Oke the changes have been made lets scan the assets directory again for changes.
         */
        $report = $scan->scan();

        /**
         * Output the changes since index action.
         */
        file_put_contents($timefile, '');
        unlink($tmpfile);

        /**
         * Output the changes since index action.
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

    } catch (Exception $e) {
        print '<pre>';
        print_r($e);
        print '</pre>';
    }

} else {
    die('This example requires mysqli to be loaded.');
}