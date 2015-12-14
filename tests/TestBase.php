<?php
namespace Redbox\Cli\Tests;

use Redbox\Cli\Cli as CLI;


class TestBase extends \PHPUnit_Framework_TestCase
{
    CONST OUR_APP = 'test.php';

    public function setUp()
    {

    }

    /** @test */
    public function it_does_nothing()
    {
        // nada
    }
}