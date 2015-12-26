<?php
namespace Redbox\Scan;

/**
 * The ScanService indexes of scans the filesystem for changes. If there are
 * any changes since the last index() then scan() will notify you about any changes
 * made to the file system (Changes or added files).
 *
 * @package Redbox\Scan
 */
class ScanService
{
    /**
     * @var Adapter\AdapterInterface $adapter;
     */
    protected $adapter;

    public function __construct(Adapter\AdapterInterface $adapter = null)
    {
        if ($adapter) {
            $this->adapter = $adapter;
        }
    }

    /**
     * Return the adapter, this could any kind of adapter.
     *
     * @return Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Index the filesystem based on the given $path. The results will the stored
     * via the given adapter.
     *
     * @param string $path
     * @param Adapter\AdapterInterface|null $adapter
     * @throws Exception\RuntimeException
     * @return Report\Report
     */
    public function index($path = "", $name="", $date="",  Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adapter must been set before calling index()');
            }
            $adapter = $this->getAdapter();
        }

        // TODO: could throw exceptions for permission denied
        // TODO: pass a name and date to index

        /**
         * Start building a basic report
         */
        $report = new Report\Report();
        $report->setName(($name) ? $name : 'a scan');
        $report->setDate(($date) ? $date : 'a date');
        $report->setPath($path);

        $activePath = $path;
        $items = array();

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $name => $object) {
            if ($object->getFilename() == '.' || $object->getFilename() == '..') {
                continue;
            }
            if ($object->isDir()) {
                $activePath = $object->getPathName();
                $items[$activePath] = array();
            } else {
                $items[$activePath][$object->getPathname()] = Filesystem\FileInfo::getFileHash($object->getRealPath());
            }
        }
        $report->setItems($items);
        if ($adapter->write($report) === false) {
            return false;
        }
        return $report;
    }


    /**
     * Scan() will scan the file system based on reports given by the adapter.
     * There should be a report from the history to call this function.
     *
     * @param Adapter\AdapterInterface|null $adapter
     * @return bool|mixed|Report\Report
     */
    public function scan(Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adapter must been set before calling scan()');
            }
            $adapter = $this->getAdapter();
        }

        // Todo: could throw exceptions for permission denied

        $report = $adapter->read();
        if ($report === false)
            return false;

        $items = $report->getItems();

        /**
         * Start building a basic report
         */
        $report = new Report\Report();
        $report->setName('a scan');
        $report->setDate(new \DateTime());
        $report->setPath($report->getPath());

        $new      = array();
        $modified = array();

        if (count($items) > 0) {
            foreach ($items as $path => $files) {
                $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
                foreach ($objects as $object) {
                    if ($object->isFile() && isset($files[$object->getPathname()])) {
                        if ($files[$object->getPathname()] != Filesystem\FileInfo::getFileHash($object->getRealPath())) {
                            $modified[] = $object;
                        }
                    } elseif ($object->isFile() && !isset($files[$object->getPathname()])) {
                        $new[] = $object;
                    }
                }
            }
        }
        $report->setModifiedFiles($modified);
        $report->setNewfiles($new);
        return $report;
    }
}