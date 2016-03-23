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
     * @var Adapter\AdapterInterface $adapter ;
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $exclude;

    /**
     * ScanService constructor.
     * @param Adapter\AdapterInterface|null $adapter
     */
    public function __construct(Adapter\AdapterInterface $adapter = null)
    {
        if ($adapter) {
            $this->adapter = $adapter;
        }

        $this->exclude = array();
    }

    /**
     * Exclude a directory from being scanned or indexed.
     *
     * @param $args
     * @return $this
     */
    public function addExclude($arg)
    {
        if (is_array($arg) == true) {
            $this->addManyExcludes($arg);
        }
        $this->exclude[] = $arg;
        print_r($this->exclude);
        return $this;
    }

    /**
     * Exclude a whole range of directories to skip while
     * scanning or indexing the filesystem.
     *
     * @param array $args
     * @return $this
     */
    public function addManyExcludes(array $args = array())
    {
        foreach ($args as $dir) {
            $this->addExclude($dir);
        }
        return $this;
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
     * @param string $name
     * @param string $date
     * @param Adapter\AdapterInterface|null $adapter
     * @return bool|Report\Report
     */
    public function index($path = "", $name = "", $date = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adapter must been set before calling index()');
            }
            $adapter = $this->getAdapter();
        }

        /**
         * Start building a basic report
         */
        $report = new Report\Report();
        $report->setName(($name) ? $name : 'a scan'); // FIXME
        $report->setDate(($date) ? $date : 'a date'); // FIXME
        $report->setPath($path);

        $activePath = $path;
        $items = array();

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);

        /** @var \SplFileInfo $object */
        foreach ($objects as $object) {

            $filename = $object->getFilename();

            if ($filename == '.' || $filename == '..') {
                continue;
            }

            $pathname = $object->getPathName();
            $realpath = $object->getRealPath();

            if ($object->isDir()) {
                $activePath = $pathname;
                $items[$activePath] = array();
            } else {
                if (isset($items[$activePath]) == true)
                $items[$activePath][$pathname] = Filesystem\FileInfo::getFileHash($realpath);
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

        $report = $adapter->read();

        if ($report === false)
            return false;

        $items = $report->getItems();
        $report->setDate(new \DateTime());

        $new = array();
        $modified = array();

        if (count($items) > 0) {
            foreach ($items as $path => $files) {
                $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
                foreach ($objects as $object) {
                    $pathname = $object->getPathname();
                    $realpath = $object->getRealPath();

                    if ($object->isFile() && isset($files[$pathname])) {
                        if ($files[$pathname] != Filesystem\FileInfo::getFileHash($realpath)) {
                            $modified[] = $object;
                        }
                    } elseif ($object->isFile() && !isset($files[$pathname])) {
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