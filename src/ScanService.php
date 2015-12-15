<?php
namespace Redbox\Scan;
use Redbox\Scan\FileSystem\RecursiveDirectoryIterator as TestDirectoryIterator;
use Redbox\Scan\Adapter\DataSource as DataSource;
use Redbox\Scan\Exception;

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
        if ($adapter)
            $this->adapter = $adapter;

    }

    /**
     * @return Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }


    // TODO: Write to collection

    public function index($path = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling index()');
            }
            $adapter = $this->getAdapter();
        }


        $data = array(
            'scan' => [
                'dame'  => 'scan',
                'date'  => @date(DATE_ATOM), // TODO: Fix date ..
                'path'  => $path,
                'items' => array(),
            ]
        );

        // TODO: This should be the relative with to
        $activePath = './';
        $items = array();

        $objects = new \RecursiveIteratorIterator(new TestDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {

            if ($object->getFilename() == '.' || $object->getFilename() == '..')
                continue;

            if ($object->isDir()) {
                $items[$path] = array();
                $activePath = $object->getPathName();
            } else {
                $items[$activePath][$object->getPathname()] = $object->getMD5Hash();
            }
        }
        $data['scan']['items'] = $items;

        $adapter->write($data);
        return $data;
    }

    public function scan($path = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling scan()');
            }
            $adapter = $this->getAdapter();
        }

        // Todo: could throw exceptions for permission denied

        $items = $adapter->getAdapter()->read()['scan']['items'];

        foreach($items as $path => $files) {
            if ($path != './tmp') continue;

            $objects = new \RecursiveIteratorIterator(new TestDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($objects as $object) {
                if ($object->isFile() && isset($files[$object->getPathname()])) {
                    if ($files[$object->getPathname()] != $object->getMD5Hash())
                        echo('Veranderd: '.$object->getPathname())."\r\n";
                } elseif ($object->isFile() && !isset($files[$object->getPathname()])) {
                        echo 'Nieuw bestand: '.$object->getPathname()."\r\n";
                }
            }
        }
    }
}