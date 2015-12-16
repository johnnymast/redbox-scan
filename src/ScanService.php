<?php
namespace Redbox\Scan;



/**
 * Note just a draft POC atm ..
 *
 */

/**
 * Class Scan
 * @package Redbox\Scan
 */
class ScanService {

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
     * @return Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }


    // TODO: Write to collection
    // Todo: could throw exceptions for permission denied

    public function index($path = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling index()');
            }
            $adapter = $this->getAdapter();
        }

        $report = new Report\Report();
        $report->setName('a scan');
        $report->setDate(new \DateTime());
        $report->setPath($path);

        // TODO: This should be the relative with to
        $activePath = $path;
        $items = array();

        $objects = new \RecursiveIteratorIterator(new Filesystem\RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {

            if ($object->getFilename() == '.' || $object->getFilename() == '..') {
                continue;
            }

            if ($object->isDir()) {
                $activePath = $object->getPathName();
                $items[$activePath] = array();
            } else {
                $items[$activePath][$object->getPathname()] = $object->getMD5Hash();
            }
        }
        $report->setItems($items);
        $adapter->write($report);
    }

    public function scan(Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling scan()');
            }
            $adapter = $this->getAdapter();
        }

        // Todo: could throw exceptions for permission denied
        $items = $adapter->read()['scan']['items'];

        foreach ($items as $path => $files) {
            if ($path != './tmp') {
                continue;
            }

            $objects = new \RecursiveIteratorIterator(new Filesystem\RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($objects as $object) {
                if ($object->isFile() && isset($files[$object->getPathname()])) {
                    if ($files[$object->getPathname()] != $object->getMD5Hash()) {
                        echo ('Veranderd: '.$object->getPathname())."\r\n";
                    }
                } elseif ($object->isFile() && !isset($files[$object->getPathname()])) {
                        echo 'Nieuw bestand: '.$object->getPathname()."\r\n";
                }
            }
        }
    }
}