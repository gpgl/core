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

    public function index(int $level = 0) : array
    {
        if ($level === 0) {
            return array_keys($this->getData());
        }

        return $this->array_keys_recursive($this->getData());
    }

    protected function array_keys_recursive(array $array) : array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $index[$key] = $this->array_keys_recursive($value);
            } else {
                $index []= $key;
            }
        }

        return $index ?? [];
    }
}
