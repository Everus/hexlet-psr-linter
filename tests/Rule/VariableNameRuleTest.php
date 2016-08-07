<?php

namespace HexletPSRLinter\Rule;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;
use HexletPSRLinter\Report;

class VariableNameRuleTest extends TestCase
{
    protected function getReportMock()
    {
        return $this->getMockBuilder(Report::class)
            ->setMethods(['addMessage'])
            ->getMock();
    }

    public function testStudlyCapsCase()
    {
        $node = new Variable('StudlyCaps');
        $report = $this->getReportMock();
        $report->expects($this->never())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }

    public function testCamelCase()
    {
        $node = new Variable('camelCase');
        $report = $this->getReportMock();
        $report->expects($this->never())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }

    public function testUnderScopeCase()
    {
        $node = new Variable('underscope_case');
        $report = $this->getReportMock();
        $report->expects($this->never())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }

    public function testUnderPrefixScopeCase()
    {
        $node = new Variable('_underscope_case');
        $report = $this->getReportMock();
        $report->expects($this->once())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }

    public function testMixedCase()
    {
        $node = new Variable('Underscope_case');
        $report = $this->getReportMock();
        $report->expects($this->once())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }

    public function testMixedStyleCase()
    {
        $node = new Variable('underscope_case');
        $node2 = new Variable('camelCase');
        $report = $this->getReportMock();
        $report->expects($this->once())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
        $rule->enterNode($node2);
    }

    public function testPropertyPropertyMethod()
    {
        $node = new PropertyProperty('_IncorrectPropertyName');
        $report = $this->getReportMock();
        $report->expects($this->atLeastOnce())
            ->method('addMessage')
            ->willReturn($report);
        $rule = new VariableNameRule($report);
        $rule->enterNode($node);
    }
}
