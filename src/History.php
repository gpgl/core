<?php

namespace gpgl\core;

use JsonSerializable;
use gpgl\core\Exceptions\InvalidHistoryChain;

class History implements JsonSerializable
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

        foreach ($chain as $link) {
            if (!is_array($link)) {
                throw new InvalidHistoryChain('All chain links must be an array.');
            }
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

        if (empty(static::array_diff_assoc_recursive($base, $target))) {
            return History::CHILD;
        }

        if (empty(static::array_diff_assoc_recursive($target, $base))) {
            return History::PARENT;
        }

        return History::DIVERGED;
    }

    public function jsonSerialize()
    {
        return $this->chain();
    }

    public function push(string $content) : History
    {
        $this->chain [] [date('c')] = sha1($content);
        return $this;
    }

    /**
     * @link https://secure.php.net/manual/en/function.array-diff-assoc.php#111675
     */
    public static function array_diff_assoc_recursive(array $array1, array $array2) : array
    {
        $difference = [];

        foreach($array1 as $key => $value) {
            if( is_array($value) ) {
                if( !isset($array2[$key]) || !is_array($array2[$key]) ) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = static::array_diff_assoc_recursive($value, $array2[$key]);
                    if( !empty($new_diff) ) {
                        $difference[$key] = $new_diff;
                    }
                }
            } else if( !array_key_exists($key,$array2) || $array2[$key] !== $value ) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}
