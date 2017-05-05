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
        return static::getFrom($this->data, ...$keys);
    }

    public static function getFrom(array $data, string ...$keys)
    {
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
            if (!is_array($data[$key] ?? null)) {
                $data[$key] = [];
            }
            $data =& $data[$key];
        }

        $data = $value;

        return $this;
    }

    public function index(int $limit = 1, string ...$keys) : array
    {
        return static::array_clean_prune($this->getData(), $limit, ...$keys);
    }

    public static function array_clean_prune(array $array, int $limit = 0, string ...$keys) : array
    {
        if (!empty($keys)) {
            $array = static::getFrom($array, ...$keys) ?? [];
        }

        --$limit;

        foreach ($array as $key => $value) {
            if (is_array($value) && $limit) {
                $index[$key] = static::array_clean_prune($value, $limit);
            } else {
                $index[$key] = '';
            }
        }

        return $index ?? [];
    }

    public function delete(string ...$keys) : Database
    {
        $data =& $this->data;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!is_array($data[$key] ?? null)) {
                return $this;
            }

            $data =& $data[$key];
        }

        unset($data[current($keys)]);

        return $this;
    }
}
