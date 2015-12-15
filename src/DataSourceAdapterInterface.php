<?php
namespace Redbox\Scan;

interface DatasourceAdapterInterface {
    public function read();
    public function write($data);
}