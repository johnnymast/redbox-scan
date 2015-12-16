<?php
namespace Redbox\Scan\Report;

// TODO: Make this class iteratable on the items so we can make the codebase in ScanService its scan() smaller

/**
 * This AbstractReport report class contains all the base tools
 * needed for Redbox\Scan\Report\Report.
 *
 * @package Redbox\Scan\Report
 */
abstract class AbstractReport
{
    /**
     * Return the items
     *
     * @var array
     */
    protected $items;

    /**
     * Report title
     *
     * @var string
     */
    protected $name = null;

    /**
     * Report path
     *
     * @var string
     */
    protected $path = null;

    /**
     * Report data
     *
     * @var \DateTime
     */
    protected $date = null;

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    abstract public function toArray();
}