<?php

use PHPUnit\Framework\TestCase;

use gpgl\core\History;

class HistoryTest extends TestCase
{
    public function test_instantiates_history_class()
    {
        $chain = [
            'b0fa0ed340041483887c9939cc16e95e307236f9',
            '5f4ef154260613cb53788d9974f6fb9bf9b6f98e',
            '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c',
            'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b',
            '7b7b92ae20d3e365a75a96bd3c840d4b51f55025',
            'b5a7f3507359dc38b69872b87bda0e9d96448448',
            '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe',
            '9392fd503334832fb49bdd245970510192d03079',
            '930882482c959f29c2f4b5dd67925c811bf5d9c6',
            '3969d65e30ea2f3b7ce682aa44529a784451cacc',
            'b46e95e6fe160736572debc7171f0c7c3041ea6b',
            '5dbf1365564d09d5d4285d646ea0a130a48d81aa',
            '7adc983386584f36e173250ede448f3847eb4cd1',
            '26bcaa1f0e9e5381c04eee12faa2ae617dfc5788',
            '93edc7e33b7b78895c8272ef76ec1e5ec6888c4a',
            'c9033615dfd76d6cb8997f6a41249860f5fe0134',
            '2e27b2011c1a4bb5656e2087b9f77759bee06dc5',
            '0de1e976ca54bcfe6ae5efa7d5012e8a2d99bd94',
            'ecb5fc6f2a729241c44f396fef31f1cf3bb03f1e',
            '935a9823c32f2a2101533f791b415fd51579611b',
            '002cb2beb3c6d306fb06cfeadb51d7d34f531fe3',
            '72c09239a2624cb560df582d53eade12b688958e',
            'e7552dcd13c1643484d0f825c7b0812742311dc8',
            '1ef919e2535b7f7bfbbc90259bacafacca5ae874',
            '925b4fca0731c5d355235152afe6234704ac336e',
            'ea12a4e3066b6e1aca041f95004688609e85a9d5',
            '9acd5207bad45ab6da64b0797834b182f6cb5c41',
            '1e7d54916b6c10ac7ca695a5b48538d0a2c7750d',
            'a5759458b043e2ea35ccb3b225696fc825e91add',
            '2710be6d3d41b0c10c3ec8e83c6d8631dbce5100',
            'd52c8807af25e51baf9cfb1962cfa7f02cc79553',
            'c48fffc2189eb26a922b41d7b15be43d3c2a3ed9',
            'b8a0d1b517b21058dd4bed3824f2721d7a8b03df',
        ];

        $history = new History($chain);

        $this->assertInstanceOf(History::class, $history);
    }

    public function test_instantiates_history_class_with_json()
    {
        $chain = json_encode([
            'b0fa0ed340041483887c9939cc16e95e307236f9',
            '5f4ef154260613cb53788d9974f6fb9bf9b6f98e',
            '8c71b7da47c90be3c7c0dc9f7e30d0cc6ca3010c',
            'f0421b1dda0fd3326280fb2fe9ae0c8ab3b4629b',
            '7b7b92ae20d3e365a75a96bd3c840d4b51f55025',
            'b5a7f3507359dc38b69872b87bda0e9d96448448',
            '3ef6612fb10a6893dbf037c2efa2cd07c38fd9fe',
            '9392fd503334832fb49bdd245970510192d03079',
            '930882482c959f29c2f4b5dd67925c811bf5d9c6',
            '3969d65e30ea2f3b7ce682aa44529a784451cacc',
            'b46e95e6fe160736572debc7171f0c7c3041ea6b',
            '5dbf1365564d09d5d4285d646ea0a130a48d81aa',
            '7adc983386584f36e173250ede448f3847eb4cd1',
            '26bcaa1f0e9e5381c04eee12faa2ae617dfc5788',
            '93edc7e33b7b78895c8272ef76ec1e5ec6888c4a',
            'c9033615dfd76d6cb8997f6a41249860f5fe0134',
            '2e27b2011c1a4bb5656e2087b9f77759bee06dc5',
            '0de1e976ca54bcfe6ae5efa7d5012e8a2d99bd94',
            'ecb5fc6f2a729241c44f396fef31f1cf3bb03f1e',
            '935a9823c32f2a2101533f791b415fd51579611b',
            '002cb2beb3c6d306fb06cfeadb51d7d34f531fe3',
            '72c09239a2624cb560df582d53eade12b688958e',
            'e7552dcd13c1643484d0f825c7b0812742311dc8',
            '1ef919e2535b7f7bfbbc90259bacafacca5ae874',
            '925b4fca0731c5d355235152afe6234704ac336e',
            'ea12a4e3066b6e1aca041f95004688609e85a9d5',
            '9acd5207bad45ab6da64b0797834b182f6cb5c41',
            '1e7d54916b6c10ac7ca695a5b48538d0a2c7750d',
            'a5759458b043e2ea35ccb3b225696fc825e91add',
            '2710be6d3d41b0c10c3ec8e83c6d8631dbce5100',
            'd52c8807af25e51baf9cfb1962cfa7f02cc79553',
            'c48fffc2189eb26a922b41d7b15be43d3c2a3ed9',
            'b8a0d1b517b21058dd4bed3824f2721d7a8b03df',
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
}
