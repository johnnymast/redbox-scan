<?php
namespace Redbox\Scan\Report;

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
    protected $items = array();

    /**
     * @var array
     */
    protected $newfiles = array();

    /**
     * @var array
     */
    protected $modifiedFiles = array();

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
     * @var string
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
     * @param array $newfiles
     */
    public function setNewfiles($newfiles)
    {
        $this->newfiles = $newfiles;
    }

    /**
     * @param array $modifiedFiles
     */
    public function setModifiedFiles($modifiedFiles)
    {
        $this->modifiedFiles = $modifiedFiles;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $date
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
     * @return array
     */
    public function getNewfiles()
    {
        return $this->newfiles;
    }

    /**
     * @return array
     */
    public function getModifiedFiles()
    {
        return $this->modifiedFiles;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * This function will convert our report into an array. This array
     * could later be converted into any output the adapter wishes.
     *
     * @return array
     */
    abstract public function toArray();
}