<?php
namespace Redbox\Scan\Adapter;

interface AdapterInterface
{
    public function read();
    public function write($data);
}