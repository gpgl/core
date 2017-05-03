<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\Database;

class DatabaseTest extends TestCase
{
    /**
     * @return array data
     *         mixed expected
     *         string,... keys
     */
    public function valuesDataProvider()
    {
        return [
            [
                ['key'=>'value'], 'value', 'key',
            ],
        ];
    }

    /**
     * @dataProvider valuesDataProvider
     */
    public function test_returns_values(array $data, $expected, string ...$keys)
    {
        $db = new Database($data);

        $actual = $db->get(...$keys);

        $this->assertEquals($expected, $actual);
    }
}
