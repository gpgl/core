<?php

namespace gpgl\core;

use gpgl\core\Exceptions\InvalidHistoryChain;

class History
{
    protected $chain = [];

    public function __construct($chain)
    {
        if (is_string($chain)) {
            $chain = json_decode($chain, $array = true);
            $error = 'JSON decode: '.json_last_error_msg();
        }

        if (!is_array($chain)) {
            throw new InvalidHistoryChain($error ?? null);
        }

        $this->chain = $chain;
    }
}
