<?php

namespace gpgl\core;

use Crypt_GPG;
use Crypt_GPG_BadPassphraseException;
use InvalidArgumentException;

class Database
{
    protected $filename;
    protected $data = [];
    protected $gpg;
    protected $key;
    protected $password;

    public function __construct(string $filename = null, string $password = null, string $key = null)
    {
        $this->gpg = new Crypt_GPG;

        $this->key = $key;
        $this->password = $password;

        if (!empty($filename)) {
            $this->import($filename, $password, $key);
        }
    }

    public static function create(string $filename, string $key, string $password = null) : Database
    {
        if (false === file_put_contents($filename, json_encode([]))) {
            throw new InvalidArgumentException("Could not write: $filename");
        }

        $db = new static($filename = null, $password, $key);
        $db->import($filename, $password = null, $key = null, $encrypted = false);

        return $db;
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

    public function import(string $filename, string $password = null, string $key = null, bool $encrypted = true) : array
    {
        if ($encrypted) {
            try {

                // default no password needed
                $json = $this->gpg->decryptFile($filename);

            } catch (Crypt_GPG_BadPassphraseException $ex) {

                if (empty($key)) {
                    $key = static::getFirstKeyIdFromEncryptedData($filename);
                }

                $json = $this->gpg
                    ->addDecryptKey($key, $password)
                    ->decryptFile($filename);

            }
        }

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
