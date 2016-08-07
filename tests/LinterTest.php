<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;

class LinterTest extends TestCase
{
    /**
    * @dataProvider additionProvider
    */
    public function testLinter($expected, $code)
    {
        $linter = new Linter();
        $report = $linter->lint($code);
        $messages = array_map(function ($message) {
            return $message->getMessage();
        }, $report->getMessages());
        $this->assertEquals($expected, $messages);
    }

    public function additionProvider()
    {
        return [
            [
                [],
                '<?php function test() {}'
            ],
            [
                ['Method names SHOULD NOT contain a single underscore'],
                '<?php function _underscore() {}'
            ],
            [
                ['Parse Error: Syntax error, unexpected EOF, expecting \'(\' on line 1'],
                '<?php function _underscore'
            ]
        ];
    }
}
