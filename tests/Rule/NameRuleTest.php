<?php

namespace HexletPSRLinter\Rule;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;
use HexletPSRLinter\Report;

class NameRuleTest extends TestCase
{
    protected function getReportMock()
    {
        return $this->getMockBuilder(Report::class)
            ->setMethods(['report'])
            ->getMock();
    }

    /**
     * @dataProvider wrongNamesProvider
     */
    public function testWrongNames($node, $expectedMessage)
    {
        $report = $this->getReportMock();
        $report->expects($this->atLeastOnce())
            ->method('report')
            ->with($expectedMessage, $this->anything(), $node);
        $rule = new NameRule($report);
        $rule->enterNode($node);
    }

    public function wrongNamesProvider()
    {
        return [
            [
                new Function_('_IncorectFuncNameWithCirilicО'),
                'Functions MUST be declared in camelCase()'
            ],
            [
                new ClassMethod('IncorrectMethodName'),
                'Methods MUST be declared in camelCase()'
            ],
            [
                new Variable('StudlyCaps'),
                'Variables MUST be declared in camelCase()'
            ],
            [
                new PropertyProperty('StudlyCaps'),
                'Properties MUST be declared in camelCase()'
            ],
        ];
    }

    /**
     * @dataProvider correctNamesProvider
     */
    public function testCorrectNames($node)
    {
        $report = $this->getReportMock();
        $report->expects($this->never())
            ->method('report');
        $rule = new NameRule($report);
        $rule->enterNode($node);
    }

    public function correctNamesProvider()
    {
        return [
            [
                new Function_('testCamelCase')
            ],
            [
                new ClassMethod('testCamelCase')
            ],
            [
                new Variable('testCamelCase')
            ],
            [
                new PropertyProperty('testCamelCase')
            ]
        ];
    }
}
