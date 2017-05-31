<?php

namespace gpgl\core;

use Crypt_GPG;
use Crypt_GPG_BadPassphraseException;
use gpgl\core\Exceptions\PreExistingFile;
use gpgl\core\Exceptions\UnwritableFile;
use gpgl\core\Exceptions\ObsoleteClient;
use Composer\Semver\Semver;

class DatabaseManagementSystem
{
    const VERSION = '1.2.1+dev';
    const VERSION_CONSTRAINT = '<2';
    protected $database;
    protected $gpg;
    protected $filename;
    protected $key;
    protected $password;
    protected $remoteManager;
    protected $history;

    public function __construct(string $filename = null, string $password = null, string $key = null)
    {
        $this->database = (new Database)->setData([
            'meta' => [
                'version' => static::VERSION,
            ],
            'data' => [],
        ]);
        $this->remoteManager = new RemoteManager;
        $this->history = new History;
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
            $dbms->setPassword($password);
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

    public function getPassword() : string
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

    public function history() : array
    {
        return $this->history->chain();
    }

    /**
     * Returns the gpgl core version number.
     *
     * @return string|null
     */
    public function version()
    {
        return $this->getMeta('version');
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

        if (empty($data['meta']['version'])) {
            $temp = [
                'meta' => [
                    'version' => static::VERSION,
                ],
                'data' => $data,
            ];

            $data = $temp;
        }

        $this->database->setData($data);

        if (!Semver::satisfies($this->version(), static::VERSION_CONSTRAINT)) {
            throw new ObsoleteClient(
                'The database was created with a newer version of gpgl. '.
                'Upgrade your client.'
            );
        }

        if (!empty($remote = $this->getMeta('remote'))) {
            $this->remote()->import($remote);
        }

        if (!empty($history = $this->getMeta('history'))) {
            $this->history = new History($history);
        }

        return $this;
    }

    public function export() : DatabaseManagementSystem
    {
        $data = json_encode($this->database->getData()['data']);
        $this->history->push($data);

        $this->setMeta(static::VERSION, 'version');
        $this->setMeta($this->remote(), 'remote');
        $this->setMeta($this->history, 'history');

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
