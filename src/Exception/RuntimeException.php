<?php
namespace Redbox\Scan\Exception;

/**
 * Our own implementation of of the RuntimeExcaption so we can
 * catch it better in the tests. Not only that but i might have future plans
 * to extend its functionality.
 *
 * @package Redbox\Scan\Exception
 */
class RuntimeException extends \RuntimeException
{
}
