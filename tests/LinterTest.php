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
        $message = $linter->lint($code)->toArray()['messages'][0]['text'];
        $this->assertEquals($expected, $message);
    }

    public function additionProvider()
    {
        return [
            [
                'Functions MUST be declared in camelCase()',
                '<?php function _underscore() {}'
            ],
            [
                'Parse Error: Syntax error, unexpected EOF, expecting \'(\' on line 1',
                '<?php function _underscore'
            ]
        ];
    }
}
