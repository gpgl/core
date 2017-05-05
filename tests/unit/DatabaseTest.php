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

    public function indexDataProvider()
    {
        return [
            [
                0,
                [
                    'first' => [
                        'second' => [
                            'third' => 'three',
                        ],
                        'deuxième' => 'two',
                    ],
                ], [
                    'first' => [
                        'second' => [
                            'third' => '',
                        ],
                        'deuxième' => '',
                    ],
                ],
            ],
            [
                1,
                [
                    'first' => [
                        'second' => [
                            'third' => 'three',
                        ],
                        'deuxième' => 'two',
                    ],
                ], [
                    'first' => '',
                ],
            ],
            [
                2,
                [
                    'first' => [
                        'second' => 'two',
                        'deuxième' => [
                            'third' => 'three',
                            'troisième' => 'three',
                        ],
                        'segundo' => 'two',
                    ],
                    'premier' => 'one',
                ], [
                    'first' => [
                        'second' => '',
                        'deuxième' => '',
                        'segundo' => '',
                    ],
                    'premier' => '',
                ],
            ],
            [
                2,
                array (
                    'uno' => array (
                        'username' => 'john',
                        'password' => 'p@s5',
                    ),
                    'dos' => array (
                        'everything' => 'all',
                        'something' => array (
                            'hither' => 'this',
                            'dither' => 'that',
                        ),
                        'anything' => 'one',
                        'nothing' => 'nil',
                    ),
                ), [
                    'uno' => [
                        'username' => '',
                        'password' => '',
                    ],
                    'dos' => [
                        'everything' => '',
                        'something' => '',
                        'anything' => '',
                        'nothing' => '',
                    ],
                ],
            ],
            [
                0,
                [
                    'first' => [
                        'second' => [
                            'third' => 'three',
                        ],
                        'deuxième' => 'two',
                    ],
                ], [
                    'second' => [
                        'third' => '',
                    ],
                    'deuxième' => '',
                ],
                'first',
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

    /**
     * @dataProvider indexDataProvider
     */
    public function test_gets_index(int $level, array $data, array $expected, string ...$keys)
    {
        $db = new Database($data);

        $actual = $db->index($level, ...$keys);

        $this->assertEquals($expected, $actual);
    }

    public function test_overwrites_primitive_with_array()
    {
        $db = new Database;

        $index = ['beg', 'mid'];
        $orig = "original";
        $empty = $db->get(...$index);
        $this->assertEmpty($empty);
        $db->set($orig, ...$index);
        $actual = $db->get(...$index);
        $this->assertEquals($orig, $actual);

        $index = ['beg', 'mid', 'end'];
        $new = "replacement";
        $db->set($new, ...$index);
        $actual = $db->get(...$index);
        $this->assertEquals($new, $actual);
    }
}
