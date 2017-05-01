<?php

namespace gpgl\core;

class Database
{
    protected $data = [];

    public function getData() : array
    {
        return $this->data;
    }

    public function setData(array $data) : Database
    {
        $this->data = $data;
        return $this;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, $value) : Database
    {
        $this->data[$key] = $value;
        return $this;
    }
}
