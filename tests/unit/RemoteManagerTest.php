<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\RemoteManager;
use gpgl\core\Remote;

class RemoteManagerTest extends TestCase
{
    public function test_instantiates_remote_manager()
    {
        $rm = new RemoteManager;
        $this->assertInstanceOf(RemoteManager::class, $rm);
    }

    public function test_imports_remote()
    {
        $rm = new RemoteManager;

        $rm->import([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $actual = $rm->get('origin');

        $this->assertInstanceOf(Remote::class, $actual);
    }

    public function test_instantiates_with_data()
    {
        $rm = new RemoteManager([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $this->assertInstanceOf(RemoteManager::class, $rm);
    }

    /**
     * @expectedException \gpgl\core\Exceptions\MissingRemote
     */
    public function test_throws_missing_remote_exception()
    {
        $rm = new RemoteManager([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $rm->get('missing');
    }

    /**
     * @expectedException \gpgl\core\Exceptions\MissingRemote
     */
    public function test_throws_default_missing_remote_exception()
    {
        $rm = new RemoteManager([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $rm->default();
    }

    /**
     * @expectedException \gpgl\core\Exceptions\MissingRemote
     */
    public function test_throws_whichDefault_missing_remote_exception()
    {
        $rm = new RemoteManager([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $rm->whichDefault();
    }

    public function test_sets_default()
    {
        $expected = 'origin';

        $rm = new RemoteManager([
            'remotes' => [
                $expected => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $rm->default($expected);

        $this->assertSame($expected, $rm->whichDefault());
    }

    /**
     * @expectedException \gpgl\core\Exceptions\MissingRemote
     */
    public function test_unsets_default()
    {
        $expected = 'origin';

        $rm = new RemoteManager([
            'remotes' => [
                $expected => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $rm->default($expected);

        $this->assertSame($expected, $rm->whichDefault());

        $rm->unsetDefault();

        $rm->default();
    }

    public function test_sets_remote()
    {
        $rm = new RemoteManager;

        $rm->set([
            'origin' => [
                'url' => 'https://gpgl.example.org/api/v1/databases/1',
                'token' => 'asdf',
            ],
        ]);

        $actual = $rm->get('origin');

        $this->assertInstanceOf(Remote::class, $actual);
    }

    /**
     * @expectedException \gpgl\core\Exceptions\MissingRemote
     */
    public function test_unsets_remote()
    {
        $rm = new RemoteManager([
            'remotes' => [
                'origin' => [
                    'url' => 'https://gpgl.example.org/api/v1/databases/1',
                    'token' => 'asdf',
                ],
            ],
        ]);

        $remote = $rm->get('origin');

        $this->assertInstanceOf(Remote::class, $remote);

        $rm->unset('origin');

        $rm->get('origin');
    }
}
