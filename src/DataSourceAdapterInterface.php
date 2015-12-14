<?php
namespace Redbox\Scan;

interface DataSourceAdapterInterface {
    public function read();
    public function write($data);
}