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
                            'third',
                        ],
                        'deuxième',
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
                    'first',
                ],
            ],
            [
                2,
                [
                    'first' => [
                        'second' => [
                            'third' => 'three',
                        ],
                        'deuxième' => 'two',
                    ],
                ], [
                    'first' => [
                        'second',
                        'deuxième',
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
                        'third',
                    ],
                    'deuxième',
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
}
