<?php
namespace Redbox\Scan\Tests\Assets;
use Redbox\Scan;


class TestBase extends \PHPUnit_Framework_TestCase
{

    protected $service;


    /**
     * Return a scan service instance
     *
     * @return Scan\ScanService
     */
    public function getNewService(Scan\Adapter\AdapterInterface $adapter = NULL) {
        return new Scan\ScanService($adapter);
    }



    /** @test */
    public function it_does_nothing()
    {
        // nada
    }
}