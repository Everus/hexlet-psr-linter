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
}
