<?php

namespace gpgl\core;

use Crypt_GPG;

class Database
{
    protected $filename;
    protected $data;
    protected $gpg;
    protected $key;
    protected $password;

    public function __construct(string $filename = null, string $key = null, string $password = null)
    {
        $this->gpg = new Crypt_GPG;

        $this->key = $key;
        $this->password = $password;

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

    protected function import(string $filename, string $key = null, string $password = null) : array
    {
        if (empty($key)) {
            $key = $this->key;
        }

        if (empty($password)) {
            $password = $this->password;
        }

        $json = $this->gpg->addDecryptKey($this->key, $this->password)
                    ->decryptFile($filename);

        $this->filename = $filename;
        $this->key = $key;
        $this->password = $password;

        return $this->data = json_decode($json, $array = true);
    }

    public function export() : bool
    {
        $json = json_encode($this->data);

        $encrypted = $this->gpg->addEncryptKey($this->key)
                        ->encrypt($json, $ascii = false);

        return false !== file_put_contents($this->filename, $encrypted);
    }
}
