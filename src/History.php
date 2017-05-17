<?php

namespace gpgl\core;

class History
{
    protected $chain = [];

    public function __construct($chain = null)
    {
        if (isset($chain)) {
            $this->import($chain);
        }
    }

    public function import($chain) : History
    {
        $this->chain = $chain;

        return $this;
    }
}
