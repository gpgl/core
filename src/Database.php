<?php

namespace gpgl\core;

class Database
{
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    public function getData() : array
    {
        return $this->data;
    }

    public function setData(array $data) : Database
    {
        $this->data = $data;
        return $this;
    }

    public function get(string ...$keys)
    {
        $data = $this->data;

        while ($key = array_shift($keys)) {
            if (!isset($data[$key])) {
                return null;
            }

            $data = $data[$key];
        }

        return $data;
    }

    public function set($value, string ...$keys) : Database
    {
        $data =& $this->data;

        while ($key = array_shift($keys)) {
            $data[$key] = $data[$key] ?? [];
            $data =& $data[$key];
        }

        $data = $value;

        return $this;
    }

    public function index(int $depth = 0) : array
    {
        return static::array_keys_recursive($this->getData(), $depth);
    }

    public static function array_keys_recursive(array $array, int $depth = -1, string ...$keys) : array
    {
        if ($depth === 0) {
            return array_keys($array);
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $index[$key] = static::array_keys_recursive($value);
            } else {
                $index []= $key;
            }
        }

        return $index ?? [];
    }
}
