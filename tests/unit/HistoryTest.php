<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\History;

class HistoryTest extends TestCase
{
    public function test_instantiates_history_class()
    {
        $chain = [
            ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
            ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
            ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
            ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
            ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
            ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
            ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
        ];

        $history = new History($chain);

        $this->assertInstanceOf(History::class, $history);
    }

    public function test_instantiates_history_class_with_json()
    {
        $chain = json_encode([
            ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
            ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
            ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
            ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
            ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
            ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
            ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
        ]);

        $history = new History($chain);

        $this->assertInstanceOf(History::class, $history);
    }

    /**
     * @expectedException \gpgl\core\Exceptions\InvalidHistoryChain
     */
    public function test_rejects_object()
    {
        $object = new \StdClass;

        $history = new History($object);

        $this->assertTrue(false);
    }

    public function historyComparisonDataProvider()
    {
        return [
            '1 SAME' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::SAME,
            ],
            '2 CHILD' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::CHILD,
            ],
            '3 PARENT' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                ]),
                'result' => History::PARENT,
            ],
            '4 DIVERGED' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                ]),
                'result' => History::DIVERGED,
            ],
            '4.1 DIVERGED' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    // different content
                    ['2017-05-30T00:41:53+00:00' => 'A30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    // different content
                    ['2017-05-30T00:41:53+00:00' => 'B30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::DIVERGED,
            ],
            '4.1 DIVERGED' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    // different timestamp
                    ['2017-05-30T00:41:53+00:00' => 'a30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'target' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    // different timestamp
                    ['2017-05-31T00:41:53+00:00' => 'a30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::DIVERGED,
            ],
            '5 UNRELATED' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                ]),
                'target' => new History([
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-30T00:41:53+00:00' => 'B30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::UNRELATED,
            ],
            '5.1 UNRELATED' => [
                'base' => new History([
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    // one coincidental shared link
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                ]),
                'target' => new History([
                    ['2017-05-17T18:28:33+00:00' => 'dffa0ed340041483887c9939cc16e95e307236f9'],
                    // one coincidental shared link
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                    ['2017-05-30T00:41:53+00:00' => 'B30882482c959f29c2f4b5dd67925c811bf5d9c6'],
                ]),
                'result' => History::UNRELATED,
            ],
            '5.2 UNRELATED' => [
                'base' => new History([
                    // different key
                    ['2017-05-19T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                ]),
                'target' => new History([
                    // different key
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                ]),
                'result' => History::UNRELATED,
            ],
            '5.3 UNRELATED' => [
                'base' => new History([
                    // different content
                    ['2017-05-18T18:28:33+00:00' => 'c0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                ]),
                'target' => new History([
                    // different content
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                ]),
                'result' => History::UNRELATED,
            ],
            '5.4 UNRELATED' => [
                'base' => new History([
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                ]),
                'target' => new History([
                    // all the same links as base + extra root and end
                    ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
                    ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
                    ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
                    ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
                    ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
                    ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
                    ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
                    ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
                ]),
                'result' => History::UNRELATED,
            ],
        ];
    }

    /**
     * @dataProvider historyComparisonDataProvider
     */
    public function test_compares_history(History $base, History $target, int $result)
    {
        $this->assertSame($result, History::compare($base, $target));
    }

    public function test_serializes_as_json()
    {
        $expected = json_encode([
            ['2017-05-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-05-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-05-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
            ['2017-05-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
            ['2017-05-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
            ['2017-05-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
            ['2017-05-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
            ['2017-05-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
            ['2017-05-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
        ]);

        $actual = json_encode(new History($expected));

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function test_pushes_history()
    {
        $start = time();
        $original = new History([
            ['2017-04-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-04-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-04-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
            ['2017-04-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
            ['2017-04-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
            ['2017-04-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
            ['2017-04-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
            ['2017-04-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
            ['2017-04-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
        ]);
        $history = clone $original;

        $sha1 = '94e66df8cd09d410c62d9e0dc59d3a884e458e05'; // some content
        $history->push('some content');

        $this->assertSame(History::CHILD, History::compare($original, $history));

        $chain = $history->chain();

        $this->assertTrue(count($chain) === count($original->chain())+1);

        $new = array_slice($chain, -1);

        $this->assertSame($sha1, current(current($new)));

        $time = (int)(DateTime::createFromFormat(ISO8601, key(current($new))))->format(UNIXTIME);

        $this->assertTrue($time >= $start);
        $this->assertTrue($time <= time());
    }

    public function test_pushes_content_concurrently()
    {
        $start = time();
        $original = new History([
            ['2017-04-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-04-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-04-21T02:01:53+00:00' => '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c'],
            ['2017-04-22T05:48:33+00:00' => 'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b'],
            ['2017-04-23T09:35:13+00:00' => '7b7b92ae20d3e365a75a96bd3c840d4b51f55025'],
            ['2017-04-24T13:21:53+00:00' => 'b5a7f3507359dc38b69872b87bda0e9d96448448'],
            ['2017-04-25T17:08:33+00:00' => '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe'],
            ['2017-04-26T20:55:13+00:00' => '9392fd503334832fb49bdd245970510192d03079'],
            ['2017-04-28T00:41:53+00:00' => '930882482c959f29c2f4b5dd67925c811bf5d9c6'],
        ]);
        $history = clone $original;

        $content1 = '94e66df8cd09d410c62d9e0dc59d3a884e458e05'; // some content
        $content2 = '1d235d579bd7a4e172c1f2268d5aa545e849de7e'; // more content
        $history->push('some content');
        $history->push('more content');

        $this->assertSame(History::CHILD, History::compare($original, $history));

        $chain = $history->chain();

        $this->assertTrue(count($chain) === count($original->chain())+2);

        $new1 = array_slice($chain, -2, 1);
        $new2 = array_slice($chain, -1);

        $this->assertSame($content1, current(current($new1)));
        $this->assertSame($content2, current(current($new2)));

        $time1 = (int)(DateTime::createFromFormat(ISO8601, key(current($new1))))->format(UNIXTIME);
        $time2 = (int)(DateTime::createFromFormat(ISO8601, key(current($new2))))->format(UNIXTIME);

        $this->assertTrue($time1 >= $start);
        $this->assertTrue($time1 <= time());

        $this->assertTrue($time2 >= $start);
        $this->assertTrue($time2 <= time());
    }

    public function test_does_not_push_without_changes()
    {
        $history = new History([
            ['2017-04-18T18:28:33+00:00' => 'b0fa0ed340041483887c9939cc16e95e307236f9'],
            ['2017-04-19T22:15:13+00:00' => '5f4ef154260613cb53788d9974f6fb9bf9b6f98e'],
            ['2017-04-21T02:01:53+00:00' => '94e66df8cd09d410c62d9e0dc59d3a884e458e05'],
        ]);

        $history->push('some content'); // 94e66df8cd09d410c62d9e0dc59d3a884e458e05

        $this->assertCount(3, $history->chain());
    }
}
