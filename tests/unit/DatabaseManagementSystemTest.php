<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\DatabaseManagementSystem;

class DatabaseManagementSystemTest extends TestCase
{
    protected $filename_pw = __DIR__.'/../fixtures/pw.gpgldb';
    protected $db_pw;
    protected $key_pw = 'jeff@example.com';
    protected $password = 'password';

    protected $filename_nopw = __DIR__.'/../fixtures/nopw.gpgldb';
    protected $db_nopw;
    protected $key_nopw = 'nopassword@example.com';

    protected function setUp()
    {
        $this->db_pw = file_get_contents($this->filename_pw);
        $this->db_nopw = file_get_contents($this->filename_nopw);
    }

    protected function tearDown()
    {
        file_put_contents($this->filename_pw, $this->db_pw);
        file_put_contents($this->filename_nopw, $this->db_nopw);
    }

    public function test_instantiates_dbms_class()
    {
        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);

        $this->assertInstanceOf(DatabaseManagementSystem::class, $dbms);
    }

    public function test_gets_index()
    {
        $expected = [
            'first',
            'second',
        ];

        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);
        $actual = $dbms->index();

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_value()
    {
        $expected = [
            "username" => "jeff",
            "password" => "pass",
        ];

        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);
        $actual = $dbms->get('first');

        $this->assertEquals($expected, $actual);
    }

    public function test_sets_value()
    {
        $expected = [
            "username" => "jose",
            "password" => "word",
        ];

        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);

        $empty = $dbms->get('test_sets_key');
        $this->assertEmpty($empty);

        $dbms->set('test_sets_key', $expected);

        $actual = $dbms->get('test_sets_key');
        $this->assertEquals($expected, $actual);
    }

    public function test_saves_dbms_to_file()
    {
        $expected = [
            "username" => "jose",
            "password" => "word",
        ];

        $orig = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);
        $empty = $orig->get('test_saves_db');
        $this->assertEmpty($empty);

        $orig->set('test_saves_db', $expected);
        $orig->export();

        $new = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);
        $actual = $new->get('test_saves_db');

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_value_without_password()
    {
        $expected = [
            "username" => "none",
            "password" => "nopw",
        ];

        $dbms = new DatabaseManagementSystem($this->filename_nopw, $password = null, $this->key_nopw);
        $actual = $dbms->get('one');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Crypt_GPG_BadPassphraseException
     */
    public function test_rejects_missing_password()
    {
        $dbms = new DatabaseManagementSystem($this->filename_pw);
        $actual = $dbms->get('first');
    }

    /**
     * @expectedException Crypt_GPG_BadPassphraseException
     */
    public function test_rejects_bad_password_with_key()
    {
        $dbms = new DatabaseManagementSystem($this->filename_pw, 'bad password', $this->key_pw);
        $actual = $dbms->get('first');
    }

    /**
     * @expectedException Crypt_GPG_BadPassphraseException
     */
    public function test_rejects_bad_password_without_key()
    {
        $dbms = new DatabaseManagementSystem($this->filename_pw, 'bad password');
        $actual = $dbms->get('first');
    }

    public function test_deduces_key_with_password()
    {
        $expected = [
            "username" => "jeff",
            "password" => "pass",
        ];

        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password);
        $actual = $dbms->get('first');

        $this->assertEquals($expected, $actual);
    }

    public function test_deduces_key_without_password()
    {
        $expected = [
            "username" => "none",
            "password" => "nopw",
        ];

        $dbms = new DatabaseManagementSystem($this->filename_nopw);
        $actual = $dbms->get('one');

        $this->assertEquals($expected, $actual);
    }

    public function test_creates_dbms()
    {
        $filename = 'test_creates_dbms.gpgldb';
        touch($filename);
        unlink($filename);
        $this->assertFileNotExists($filename);

        $dbms = DatabaseManagementSystem::create($filename, $this->key_nopw);
        $this->assertFileExists($filename);

        $this->assertInstanceOf(DatabaseManagementSystem::class, $dbms);

        unlink($filename);
    }
}
