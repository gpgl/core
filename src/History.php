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
        $base = $base->chain();
        $target = $target->chain();

        if (array_slice($base, 0, 1) !== array_slice($target, 0, 1)) {
            return History::UNRELATED;
        }

        if ($base === $target) {
            return History::SAME;
        }

        if (empty(array_diff_assoc($base, $target))) {
            return History::CHILD;
        }

        if (empty(array_diff_assoc($target, $base))) {
            return History::PARENT;
        }

        return History::DIVERGED;
    }
}
