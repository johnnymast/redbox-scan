<?php
namespace Redbox\Scan\Report;

/* TODO: Make array */

abstract class AbstractReport implements ReportInterface
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


    public function __construct()
    {
    }

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
     * @param mixed $path
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


    public function toArray() {
        return array(
            'scan' => array(
                'name' => $this->getName(),
                'date' => $this->getDate(),
                'path' => $this->getPath(),
                'items' => $this->getItems(),
            )
        );
    }





}