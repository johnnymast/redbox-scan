<?php
namespace Redbox\Scan\Datasource;

class Datasource {

    /**
     * @var \Redbox\Scan\Datasource\AbstractAdapter
     */
    protected $adaptor;

    public function setAdaptor($adaptor) {
        $this->adaptor = $adaptor;
    }

    /**
     * @return \Redbox\Scan\Datasource\AbstractAdapter;
     */
    public function getAdaptor() {
        return $this->adaptor;
    }

    public function getSignatures() {

    }
}
