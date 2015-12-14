<?php
namespace Redbox\Scan;


class Scan {


    public function __construct($path="")
    {


    }

    public function scan($path)
    {
        $files = array();
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){
            print_r($object);
            $files[] = $name;
        }
        return $files;
    }
}