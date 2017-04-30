<?php

namespace gpgl\core;

class Database
{
    protected $filename;
    protected $data;

    public function __construct(string $filename = null)
    {
        if (empty($filename)) {
            $filename = getenv('GPGL_DB');
        }

        $this->import($filename);
    }

    public function index() : array
    {
        return array_keys($this->data);
    }

    public function get(string $key) : array
    {
        return $this->data[$key] ?? [];
    }

    public function set(string $key, array $values) : bool
    {
        return $values === $this->data[$key] = $values;
    }

    protected function import(string $filename) : array
    {
        $json = file_get_contents($this->filename = $filename);
        return $this->data = json_decode($json, $array = true);
    }

    public function export() : bool
    {
        $json = json_encode($this->data);
        return false !== file_put_contents($this->filename, $json);
    }
}
