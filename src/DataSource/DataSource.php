<?php
namespace Redbox\DataSource;

class Source {
    protected $adaptor;

    public function setAdaptor($adaptor) {
        $this->adaptor = $adaptor;
    }
}
