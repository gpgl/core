<?php

namespace gpgl\core;

use JsonSerializable;
use gpgl\core\Exceptions\MissingRemote;

class RemoteManager implements JsonSerializable
{
    protected $remotes = [];
    protected $default;

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

    public function default(string $name = null) : Remote
    {
        if (!is_null($name)) {
            $this->default = $name;
        }

        if (is_null($this->default)) {
            throw new MissingRemote("A default remote has not been set.");
        }

        return $this->get($this->default);
    }

    public function whichDefault() : string
    {
        if (is_null($this->default)) {
            throw new MissingRemote("A default remote has not been set.");
        }

        return $this->default;
    }

    public function import(array $remoteManager) : RemoteManager
    {
        $this->set($remoteManager['remotes']);

        if (isset($remoteManager['default'])) {
            $this->default($remoteManager['default']);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'default' => $this->default,
            'remotes' => $this->remotes,
        ];
    }
}
