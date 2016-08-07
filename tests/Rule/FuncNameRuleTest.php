<?php

namespace HexletPSRLinter\Rule;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use HexletPSRLinter\Linter;

class FuncNameRuleTest extends TestCase
{
    protected function getLinterMock()
    {
        return $this->getMockBuilder(Linter::class)
            ->setMethods(['addReport'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testStrongCase()
    {
        $node = new Function_('StrongTestName');
        $linter = $this->getLinterMock();
        $linter->expects($this->once())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new FuncNameRule($linter);
        $rule->enterNode($node);
    }

    public function testCamelCase()
    {
        $node = new Function_('camelCase');
        $linter = $this->getLinterMock();
        $linter->expects($this->never())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new FuncNameRule($linter);
        $rule->enterNode($node);
    }

    public function testClassMethod()
    {
        $node = new ClassMethod('IncorrectMethodName');
        $linter = $this->getLinterMock();
        $linter->expects($this->once())
            ->method('addReport')
            ->willReturn($linter);
        $rule = new FuncNameRule($linter);
        $rule->enterNode($node);
    }

    public function testNameWithTwoProblems()
    {
        $node = new Function_('_IncorectFuncNameWithCirilicО');
        $linter = $this->getLinterMock();
        $linter->expects($this->exactly(2))
            ->method('addReport')
            ->willReturn($linter);
        $rule = new FuncNameRule($linter);
        $rule->enterNode($node);
    }
}
