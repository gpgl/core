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
            [
                [
                    'first' =>
                    [
                        'second' => 'value'
                    ]
                ], 'value', 'first', 'second',
            ],
            [
                [
                    'first' =>
                    [
                        'second' => 'value'
                    ]
                ], null, 'nada', 'second',
            ],
            [
                [
                    'first' =>
                    [
                        'second' => 'value'
                    ]
                ], null, 'first', 'second', 'third',
            ],
        ];
    }

    /**
     * @dataProvider valuesDataProvider
     */
    public function test_gets_values(array $data, $expected, string ...$keys)
    {
        $db = new Database($data);

        $actual = $db->get(...$keys);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider valuesDataProvider
     */
    public function test_sets_values(array $data, $expected, string ...$keys)
    {
        $db = new Database;

        $db->set($expected, ...$keys);
        $actual = $db->get(...$keys);

        $this->assertEquals($expected, $actual);
    }
}
