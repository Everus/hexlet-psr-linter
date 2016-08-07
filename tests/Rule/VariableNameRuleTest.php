<?php

namespace HexletPSRLinter\Rule;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;
use HexletPSRLinter\Linter;

class VariableNameRuleTest extends TestCase
{
    protected function getLinterMock()
    {
        return $this->getMockBuilder(Linter::class)
            ->setMethods(['addReport'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testStudlyCapsCase()
    {
        $node = new Variable('StudlyCaps');
        $linter = $this->getLinterMock();
        $linter->expects($this->never())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }

    public function testCamelCase()
    {
        $node = new Variable('camelCase');
        $linter = $this->getLinterMock();
        $linter->expects($this->never())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }

    public function testUnderScopeCase()
    {
        $node = new Variable('underscope_case');
        $linter = $this->getLinterMock();
        $linter->expects($this->never())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }

    public function testUnderPrefixScopeCase()
    {
        $node = new Variable('_underscope_case');
        $linter = $this->getLinterMock();
        $linter->expects($this->once())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }

    public function testMixedCase()
    {
        $node = new Variable('Underscope_case');
        $linter = $this->getLinterMock();
        $linter->expects($this->once())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }

    public function testMixedStyleCase()
    {
        $node = new Variable('underscope_case');
        $node2 = new Variable('camelCase');
        $linter = $this->getLinterMock();
        $linter->expects($this->once())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
        $rule->enterNode($node2);
    }

    public function testPropertyPropertyMethod()
    {
        $node = new PropertyProperty('_IncorrectPropertyName');
        $linter = $this->getLinterMock();
        $linter->expects($this->atLeastOnce())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new VariableNameRule($linter);
        $rule->enterNode($node);
    }
}
