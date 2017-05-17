<?php

namespace gpgl\core;

use gpgl\core\Exceptions\InvalidHistoryChain;

class History
{
    const SAME = 100;
    const CHILD = 200;
    const PARENT = 300;
    const DIVERGED = 400;
    const UNRELATED = 500;

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

    public function chain() : array
    {
        return $this->chain;
    }

    public static function compare(History $base, History $target) : int
    {
        foreach ($base->chain() as $time => $content) {
            if (!isset($target->chain()[$time])) {
                return History::CHILD;
            }
        }

        return History::SAME;
    }
}
