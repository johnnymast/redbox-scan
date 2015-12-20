<?php
require '../vendor/autoload.php';

use Redbox\Scan\Adapter;
use Redbox\Scan\Report\Report;


class Database extends \mysqli implements Adapter\AdapterInterface
{
    CONST SCAN_ID = 1;

    public function __construct($host, $user, $pass, $db)
    {
        parent::__construct();
        parent::connect($host, $user, $pass, $db);
    }

    private function getScan()
    {
        $sql = sprintf("SELECT *, scandate as `date` FROM `scan` WHERE `id`='%s'", self::SCAN_ID);
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

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
        return $report->toArray();
    }

    // TODO: This should be an universial exception
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
            "scan");

        $scan = new Redbox\Scan\ScanService($databaseAdaptor);

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

       // $x = $databaseAdaptor->read();
       // print_r($x->getItems());


    } catch (Exception $e) {
        print '<pre>';
        print_r($e);
        print '</pre>';
    }



} else {
    die('This example requires mysqli to be loaded.');
}