<?php
namespace Redbox\Scan\Adaptor;

class Datasource {

    /**
     * @var \Redbox\Scan\Adapter\AbstractAdapter
     */
    protected $adaptor;

    public function setAdaptor($adaptor) {
        $this->adaptor = $adaptor;
    }

    /**
     * @return \Redbox\Scan\Adapter\AbstractAdapter;
     */
    public function getAdaptor() {
        return $this->adaptor;
    }

    public function getSignatures() {

    }
}
