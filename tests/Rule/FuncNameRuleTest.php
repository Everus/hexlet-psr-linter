<?php

namespace HexletPSRLinter\Rule;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use HexletPSRLinter\Report;

class FuncNameRuleTest extends TestCase
{
    protected function getReportMock()
    {
        return $this->getMockBuilder(Report::class)
            ->setMethods(['addMessage'])
            ->getMock();
    }

    public function testStrongCase()
    {
        $node = new Function_('StrongTestName');
        $report = $this->getReportMock();
        $report->expects($this->once())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new FuncNameRule($report);
        $rule->enterNode($node);
    }

    public function testCamelCase()
    {
        $node = new Function_('camelCase');
        $report = $this->getReportMock();
        $report->expects($this->never())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new FuncNameRule($report);
        $rule->enterNode($node);
    }

    public function testClassMethod()
    {
        $node = new ClassMethod('IncorrectMethodName');
        $report = $this->getReportMock();
        $report->expects($this->once())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new FuncNameRule($report);
        $rule->enterNode($node);
    }

    public function testNameWithTwoProblems()
    {
        $node = new Function_('_IncorectFuncNameWithCirilicО');
        $report = $this->getReportMock();
        $report->expects($this->exactly(2))
            ->method('addMessage')
            ->willReturn($report);
        $rule = new FuncNameRule($report);
        $rule->enterNode($node);
    }
}
