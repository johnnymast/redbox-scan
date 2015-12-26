<?php
namespace Redbox\Scan\Report;
use Redbox\Scan\Exception;

class Report extends AbstractReport
{
    /**
     * Return the report in array form.
     *
     * @return array
     */
    public function toArray() {
        return array(
            'name'  => $this->getName(),
            'date'  => $this->getDate(),
            'path'  => $this->getPath(),
            'items' => $this->getItems(),
        );
    }

    /**
     * Return an instance of Report by providing an array to the method.
     *
     * @param array $array
     * @return Report
     */
    static function fromArray($array = array()) {
        $required = array('name','date','path','items');
        $report = new Report();

        /**
         * Check if all required fields are being set.
         */
        foreach ($required as $req) {
            if (isset($array[$req]) === false) {
                throw new Exception\RuntimeException('Could not create a report from this array field ' . $req . ' was not set the following fields are required (' . implode(',', $required) . ')');
            }
        }
        $report->setName($array['name']);
        $report->setDate($array['date']);
        $report->setPath($array['path']);
        $report->setItems($array['items']);
        return $report;
    }

}