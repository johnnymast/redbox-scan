<?php
namespace Redbox\Scan\DataSource;

class DataSource {

    /**
     * @var \Redbox\Scan\DataSource\AbstractAdapter
     */
    protected $adaptor;

    public function setAdaptor($adaptor) {
        $this->adaptor = $adaptor;
    }

    /**
     * @return \Redbox\Scan\DataSource\AbstractAdapter;
     */
    public function getAdaptor() {
        return $this->adaptor;
    }

    public function getSignatures() {

    }
}
