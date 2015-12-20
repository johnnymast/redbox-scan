<?php
namespace Redbox\Scan\Report;

class Report extends AbstractReport
{
    /**
     * Return the report in array form.
     *
     * @return array
     */
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