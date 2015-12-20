<?php
namespace Redbox\Scan\Report;

// TODO: Make this class inevitable on the items so we can make the codebase in ScanService its scan() smaller

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
    protected $items = [];

    /**
     * @var array
     */
    protected $newfiles = [];
    /**
     * @var array
     */
    protected $modifiedFiles = [];

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


    public function __construct($array = [])
    {
        $this->mapTypes($array);
    }

    /**
     * Convert a string to camelCase
     * @param  string $value
     * @return string
     */
    public static function camelCase($value)
    {
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        $value = str_replace(' ', '', $value);
        $value[0] = strtolower($value[0]);
        return $value;
    }

    /**
     * Initialize this object's properties from an array.
     *
     * @param array $array Used to seed this object's properties.
     * @return void
     */
    protected function mapTypes($array)
    {

        // Hard initialise simple types, lazy load more complex ones.
        foreach ($array as $key => $val) {
            if (!property_exists($this, $this->keyType($key)) &&
                property_exists($this, $key)) {
                $this->$key = $val;
                unset($array[$key]);
            } elseif (property_exists($this, $camelKey = self::camelCase($key))) {
                // This checks if property exists as camelCase, leaving it in array as snake_case
                // in case of backwards compatibility issues.
                $this->$camelKey = $val;
            }
        }
        $this->modelData = $array;
    }

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

    protected function keyType($key)
    {
        return $key."Type";
    }

    protected function dataType($key)
    {
        return $key."DataType";
    }

    public function __isset($key)
    {
        return isset($this->modelData[$key]);
    }

    public function __unset($key)
    {
        unset($this->modelData[$key]);
    }


    /**
     * This function will convert our report into an array. This array
     * could later be converted into any output the adaptor wishes.
     *
     * @return array
     */
    abstract public function toArray();
}