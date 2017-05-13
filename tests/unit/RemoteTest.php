<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\Remote;

class RemoteTest extends TestCase
{
    public function test_creates_remote()
    {
        $data = [
            'url' => 'https://gpgl.example.org/api/v1/databases/1',
            'token' => 'RPAqQ^q1x46N&xDaLIBjQm?.5FCvss6_',
        ];

        $remote = new Remote($data);

        $this->assertInstanceOf(Remote::class, $remote);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_rejects_missing_url()
    {
        $data = [
            'token' => 'RPAqQ^q1x46N&xDaLIBjQm?.5FCvss6_',
        ];

        $remote = new Remote($data);

        $this->assertTrue(false);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_rejects_missing_token()
    {
        $data = [
            'url' => 'https://gpgl.example.org/api/v1/databases/1',
        ];

        $remote = new Remote($data);

        $this->assertTrue(false);
    }
}
