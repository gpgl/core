<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\Database;

class DatabaseTest extends TestCase
{
    protected $filename_pw = __DIR__.'/../fixtures/pw.gpgldb';
    protected $database_pw;
    protected $key_pw = 'jeff@example.com';
    protected $password = 'password';

    protected $filename_nopw = __DIR__.'/../fixtures/nopw.gpgldb';
    protected $database_nopw;
    protected $key_nopw = 'nopassword@example.com';

    protected function setUp()
    {
        putenv('GPGL_DB');
        $this->database_pw = file_get_contents($this->filename_pw);
        $this->database_nopw = file_get_contents($this->filename_nopw);
    }

    protected function tearDown()
    {
        putenv('GPGL_DB');
        file_put_contents($this->filename_pw, $this->database_pw);
        file_put_contents($this->filename_nopw, $this->database_nopw);
    }

    public function test_creates_database()
    {
        $db = new Database($this->filename_pw, $this->key_pw, $this->password);

        $this->assertInstanceOf(Database::class, $db);
    }

    public function test_gets_index()
    {
        $expected = [
            'first',
            'second',
        ];

        $db = new Database($this->filename_pw, $this->key_pw, $this->password);
        $actual = $db->index();

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_index_from_filename_pw_env()
    {
        putenv("GPGL_DB={$this->filename_pw}");
        $expected = [
            'first',
            'second',
        ];

        $db = new Database($filename_pw = null, $this->key_pw, $this->password);
        $actual = $db->index();

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_value()
    {
        $expected = [
            "username" => "jeff",
            "password" => "pass",
        ];

        $db = new Database($this->filename_pw, $this->key_pw, $this->password);
        $actual = $db->get('first');

        $this->assertEquals($expected, $actual);
    }

    public function test_sets_value()
    {
        $expected = [
            "username" => "jose",
            "password" => "word",
        ];

        $db = new Database($this->filename_pw, $this->key_pw, $this->password);
        $empty = $db->get('test_sets_key');
        $this->assertEmpty($empty);

        $this->assertTrue($db->set('test_sets_key', $expected));
        $actual = $db->get('test_sets_key');

        $this->assertEquals($expected, $actual);
    }

    public function test_saves_database_to_file()
    {
        $expected = [
            "username" => "jose",
            "password" => "word",
        ];

        $orig = new Database($this->filename_pw, $this->key_pw, $this->password);
        $empty = $orig->get('test_saves_db');
        $this->assertEmpty($empty);
        $orig->set('test_saves_db', $expected);

        $this->assertTrue($orig->export());
        $new = new Database($this->filename_pw, $this->key_pw, $this->password);
        $actual = $new->get('test_saves_db');

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_value_without_password()
    {
        $expected = [
            "username" => "none",
            "password" => "nopw",
        ];

        $db = new Database($this->filename_nopw, $this->key_nopw);
        $actual = $db->get('one');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Crypt_GPG_BadPassphraseException
     */
    public function test_rejects_missing_password()
    {
        $db = new Database($this->filename_pw, $this->key_pw);
        $actual = $db->get('first');
    }

    /**
     * @expectedException Crypt_GPG_BadPassphraseException
     */
    public function test_rejects_bad_password()
    {
        $db = new Database($this->filename_pw, $this->key_pw, 'bad password');
        $actual = $db->get('first');
    }
}
