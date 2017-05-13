<?php

namespace gpgl\core;

use InvalidArgumentException;

class Remote
{
    protected $url = '';
    protected $token = '';

    public function __construct(array $data = null)
    {
        if (!empty($data)) {
            if (empty($data['url'])) {
                throw new InvalidArgumentException('URL cannot be empty.');
            }
            $this->url($data['url']);

            if (empty($data['token'])) {
                throw new InvalidArgumentException('token cannot be empty.');
            }
            $this->token($data['token']);
        }
    }

    public function url(string $url = null) : string
    {
        if (!empty($url)) {
            $this->url = $url;
        }

        return $this->url;
    }

    public function token(string $token = null) : string
    {
        if (!empty($token)) {
            $this->token = $token;
        }

        return $this->token;
    }
}
