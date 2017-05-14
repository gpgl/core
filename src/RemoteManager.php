<?php

namespace gpgl\core;

use gpgl\core\Exceptions\MissingRemote;

class RemoteManager
{
    protected $remotes = [];

    public function get(string $name) : Remote
    {
        if (empty($this->remotes[$name])) {
            throw new MissingRemote("Remote '$name' does not exist.");
        }

        return $this->remotes[$name];
    }

    public function set(array $remotes) : RemoteManager
    {
        foreach ($remotes as $name => $remote) {
            if (!$remote instanceof Remote) {
                $remote = new Remote($remote);
            }

            $this->remotes[$name] = $remote;
        }

        return $this;
    }

    public function export() : array
    {
        return $this->remotes;
    }

    public function import(array $remoteManager) : RemoteManager
    {
        $this->set($remoteManager['remotes']);

        return $this;
    }
}
