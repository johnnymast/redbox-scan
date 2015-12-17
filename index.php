<?php
require 'vendor/autoload.php';
/*
use Redbox\Cli\Cli as CLI;

use Redbox\Scan\Adapter\Filesystem as FilesystemAdaptor;
use Redbox\Scan;

function commandLine() {
    try {
        $cli = new CLI;
        $cli->arguments->add([
            'path' => [
                'prefix'       => 'p',
                'longPrefix'   => 'path',
                'description'  => 'Path to scan',
                'defaultValue' => './',
                'required'     => true,
            ]
        ]);
        $cli->arguments->parse();
        return $cli->arguments->get('path');


    } catch (Exception $e) {
        $cli->arguments->usage();
    }
}


$path = commandLine();
*/


$path = "./";
$scan = new Redbox\Scan\ScanService(new Redbox\Scan\Adapter\Filesystem('tmp/data.yml'));
$scan->index($path);

//$files = $scan->scan($path);


echo 'Path: '.$path."\n";





