<?php

namespace gpgl\core;

use Crypt_GPG;
use Crypt_GPG_BadPassphraseException;
use gpgl\core\Exceptions\PreExistingFile;
use gpgl\core\Exceptions\UnwritableFile;

class DatabaseManagementSystem
{
    protected $database;
    protected $gpg;
    protected $filename;
    protected $key;
    protected $password;
    protected $remoteManager;

    public function __construct(string $filename = null, string $password = null, string $key = null)
    {
        $this->database = new Database;
        $this->remoteManager = new RemoteManager;
        $this->gpg = new Crypt_GPG;

        if (isset($password)) {
            $this->setPassword($password);
        }

        if (isset($key)) {
            $this->setKey($key);
        }

        if (isset($filename)) {
            $this->setFilename($filename)->import();
        }
    }

    public static function create(string $filename, string $key, string $password = null) : DatabaseManagementSystem
    {
        if (file_exists($filename)) {
            throw new PreExistingFile("File already exists: $filename");
        }

        $dbms = new static;

        $dbms->setFilename($filename)->setKey($key);

        if (isset($password)) {
            $this->setPassword($password);
        }

        $dbms->export();

        return $dbms;
    }

    public function index(int $limit = 1, string ...$keys) : array
    {
        $keys = array_merge(['data'], $keys);
        return $this->database->index($limit, ...$keys);
    }

    public function get(string ...$keys)
    {
        $keys = array_merge(['data'], $keys);
        return $this->database->get(...$keys);
    }

    public function set($values, string ...$keys) : DatabaseManagementSystem
    {
        $keys = array_merge(['data'], $keys);
        $this->database->set($values, ...$keys);
        return $this;
    }

    public function delete(string ...$keys) : DatabaseManagementSystem
    {
        $keys = array_merge(['data'], $keys);
        $this->database->delete(...$keys);
        return $this;
    }

    protected function getMeta(string ...$keys)
    {
        $keys = array_merge(['meta'], $keys);
        return $this->database->get(...$keys);
    }

    protected function setMeta($values, string ...$keys) : DatabaseManagementSystem
    {
        $keys = array_merge(['meta'], $keys);
        $this->database->set($values, ...$keys);
        return $this;
    }

    protected function deleteMeta(string ...$keys) : DatabaseManagementSystem
    {
        $keys = array_merge(['meta'], $keys);
        $this->database->delete(...$keys);
        return $this;
    }

    public function getFilename() : string
    {
        return $this->filename;
    }

    public function setFilename(string $filename) : DatabaseManagementSystem
    {
        $this->filename = $filename;
        return $this;
    }

    public function getKey() : string
    {
        if (empty($this->key)) {
            $this->key = static::getFirstKeyIdFromEncryptedData($this->getFilename());
        }

        return $this->key;
    }

    public function setKey(string $key) : DatabaseManagementSystem
    {
        $this->key = $key;
        return $this;
    }

    protected function getPassword() : string
    {
        if (is_null($this->password)) {
            throw new Crypt_GPG_BadPassphraseException('No password provided.');
        }

        return $this->password;
    }

    public function setPassword(string $password) : DatabaseManagementSystem
    {
        $this->password = $password;
        return $this;
    }

    public function remote() : RemoteManager
    {
        return $this->remoteManager;
    }

    public function import() : DatabaseManagementSystem
    {
        try {

            // default no password needed
            $json = $this->gpg->decryptFile($this->getFilename());

        } catch (Crypt_GPG_BadPassphraseException $ex) {

            $json = $this->gpg
                ->addDecryptKey($this->getKey(), $this->getPassword())
                ->decryptFile($this->getFilename());

        }

        $data = json_decode($json, $array = true);

        $this->database->setData($data);

        if (!empty($remote = $this->getMeta('remote'))) {
            $this->remote()->import($remote);
        }

        return $this;
    }

    public function export() : DatabaseManagementSystem
    {
        $this->setMeta($this->remote(), 'remote');

        $json = json_encode($this->database->getData());

        $encryptedData = $this->gpg
            ->addEncryptKey($this->getKey())
            ->encrypt($json, $ascii = false);

        $filename = $this->getFilename();

        if (false === file_put_contents($filename, $encryptedData)) {
            throw new UnwritableFile("Could not write file: $filename");
        }

        return $this;
    }

    /**
     * @param  string $filename Name of encrypted file.
     * @return string The key ID of the *first* recipient.
     */
    public static function getFirstKeyIdFromEncryptedData(string $filename) : string
    {
        $filename = escapeshellarg($filename);
        $output = `2>&1 gpg --list-only --verbose $filename`;
        $lines = explode(PHP_EOL, $output);
        $words = explode(' ', $lines[0]);
        return end($words);
    }
}
