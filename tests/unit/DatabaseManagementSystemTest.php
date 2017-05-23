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

    protected $filename_temp = __DIR__.'/../fixtures/test_creates_dbms.gpgldb';

    protected function setUp()
    {
        $this->db_pw = file_get_contents($this->filename_pw);
        $this->db_nopw = file_get_contents($this->filename_nopw);
    }

    protected function tearDown()
    {
        file_put_contents($this->filename_pw, $this->db_pw);
        file_put_contents($this->filename_nopw, $this->db_nopw);

        if (file_exists($this->filename_temp)) {
            unlink(realpath($this->filename_temp));
        }
    }

    public function test_instantiates_dbms_class()
    {
        $dbms = new DatabaseManagementSystem($this->filename_pw, $this->password, $this->key_pw);

        $this->assertInstanceOf(DatabaseManagementSystem::class, $dbms);
    }

    public function test_gets_index()
    {
        $expected = [
            'first' => '',
            'second' => '',
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

        $dbms->set($expected, 'test_sets_key');

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

        $orig->set($expected, 'test_saves_db');
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
        $filename = $this->filename_temp;
        touch($filename);
        unlink($filename);
        $this->assertFileNotExists($filename);

        $dbms = DatabaseManagementSystem::create($filename, $this->key_nopw);
        $this->assertFileExists($filename);

        $this->assertInstanceOf(DatabaseManagementSystem::class, $dbms);
    }

    /**
     * @expectedException \gpgl\core\Exceptions\PreExistingFile
     */
    public function test_rejects_clobber_creation()
    {
        $filename = $this->filename_temp;
        touch($filename);
        $this->assertFileExists($filename);

        $dbms = DatabaseManagementSystem::create($filename, $this->key_nopw);
    }

    public function test_gets_remote_url()
    {
        $expected = 'https://gpgl.example.org/api/v1/databases/1';

        $dbms = new DatabaseManagementSystem($this->filename_nopw);
        $actual = $dbms->remote()->get('origin')->url();

        $this->assertEquals($expected, $actual);
    }

    public function test_gets_remote_token()
    {
        $expected = 'RPAqQ^q1x46N&xDaLIBjQm?.5FCvss6_';

        $dbms = new DatabaseManagementSystem($this->filename_nopw);
        $actual = $dbms->remote()->get('origin')->token();

        $this->assertEquals($expected, $actual);
    }

    public function test_sets_remote()
    {
        $expected = [
            'fortytwo' => [
                'url' => 'https://gpgl.example.org/api/v1/databases/42',
                'token' => 'n3jBnlz|.G_syNA13dbkRYQo^DP_XgwB',
            ],
            'fiftythree' => [
                'url' => 'https://gpgl.example.org/api/v1/databases/53',
                'token' => 'K>7~RE3iLyF?1F87vs&L{r^-Oe5kkSs_',
            ],
        ];

        $dbms = new DatabaseManagementSystem($this->filename_nopw);
        $dbms->remote()->set($expected);
        $dbms->export();

        $dbms = new DatabaseManagementSystem($this->filename_nopw);

        $this->assertEquals(
            $expected['fortytwo']['token'],
            $dbms->remote()->get('fortytwo')->token()
        );

        $this->assertEquals(
            $expected['fiftythree']['url'],
            $dbms->remote()->get('fiftythree')->url()
        );
    }

    public function test_saves_default_remote()
    {
        $expected = [
            'fortytwo' => [
                'url' => 'https://gpgl.example.org/api/v1/databases/42',
                'token' => 'n3jBnlz|.G_syNA13dbkRYQo^DP_XgwB',
            ],
            'fiftythree' => [
                'url' => 'https://gpgl.example.org/api/v1/databases/53',
                'token' => 'K>7~RE3iLyF?1F87vs&L{r^-Oe5kkSs_',
            ],
        ];

        $dbms = new DatabaseManagementSystem($this->filename_nopw);
        $dbms->remote()->set($expected)->default('fortytwo');
        $dbms->export();

        $dbms = new DatabaseManagementSystem($this->filename_nopw);

        $this->assertSame('fortytwo', $dbms->remote()->whichDefault());

        $this->assertEquals(
            $expected['fortytwo']['token'],
            $dbms->remote()->default()->token()
        );
    }

    public function test_saves_history()
    {
        $dbms1 = new DatabaseManagementSystem($this->filename_nopw);

        $dbms1->set('something', 'test_saves_some_history');
        $dbms1->export();

        $dbms2 = new DatabaseManagementSystem($this->filename_nopw);
        $this->assertCount(3, $dbms2->history());

        $dbms2->set('anything', 'test_saves_more_history');
        $dbms2->export();

        $dbms3 = new DatabaseManagementSystem($this->filename_nopw);
        $this->assertCount(4, $dbms3->history());
    }

    public function test_gets_version_number()
    {
        $expected = '1.0.0';

        $dbms = new DatabaseManagementSystem($this->filename_nopw);

        $actual = $dbms->version();

        $this->assertSame($expected, $actual);
    }

    public function test_imports_new_data_from_json()
    {
        $filename = __DIR__.'/../fixtures/pw.new.json.gpg';
        $expected = [
            "something" => "",
            "anything" => "",
        ];

        $dbms = new DatabaseManagementSystem($filename, $this->password);

        $actual = $dbms->index();

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \gpgl\core\Exceptions\ObsoleteClient
     */
    public function test_throws_exception_for_incompatible_new_version()
    {
        $filename = __DIR__.'/../fixtures/pw.2.gpgldb';

        $dbms = new DatabaseManagementSystem($filename, $this->password);
    }
}
