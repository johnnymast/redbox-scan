<?php
namespace Redbox\Scan;

interface SourceAdapterInterface {
    public function read();
    public function write();
}