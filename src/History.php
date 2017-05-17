<?php

namespace gpgl\core;

class History
{
    protected $chain = [];

    public function __construct($chain)
    {
        $this->chain = $chain;
    }
}
