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


    public function index($path = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling index()');
            }
        }


        $objects = new \RecursiveIteratorIterator(new TestDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        $data = array(
            'scan' => [
                'dame'  => 'scan',
                'date'  => @date(DATE_ATOM),
                'path'  => $path,
                'items' => array(),
            ]
        );
        $activePath = './';

        foreach ($objects as $name => $object){
            if ($object->isDir() and $object->getFilename()[0] === '.'){
                continue;
            }
            $path = $object->getPathName();
            print_r($object->filename)."\n\n";
         //   if ($path !== 'tmp') continue;


            if ($object->isDir()) {
                $items[$path] = array();
                $activePath = $path;
            } else {
                $items[$activePath][$object->getPathname()] = $object->getMD5Hash();
            }
        }
        $data['scan']['items'] = $items;
        $this->getAdapter()->write($data);
        return $data;
    }

    public function scan($path = "", Adapter\AdapterInterface $adapter = null)
    {
        if (!$adapter) {
            if (!$this->getAdapter()) {
                throw new Exception\RuntimeException('An Adaptor must been set before calling scan()');
            }
        }

        $objects = new \RecursiveIteratorIterator(new TestDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        $items = $this->datasource->getAdapter()->read()['scan']['items'];

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