<?php
require 'vendor/autoload.php';

use Redbox\Cli\Cli as CLI;
use Redbox\Scan\Scan as Scan;

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

$scan = new Scan();

$adaptor = new Redbox\Scan\DataSource\Filesystem('tmp/data.yml');

$scan->datasource->setAdaptor($adaptor);
$scan->index($path);

//$files = $scan->scan($path);



print_r($files);

echo 'Path: '.$path."\n";