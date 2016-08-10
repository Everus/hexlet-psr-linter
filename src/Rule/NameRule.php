<?php

namespace HexletPSRLinter\Rule;

use HexletPSRLinter\Report;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;

class NameRule extends RuleAbstract
{
    const CAMEL_CASE_PATTERN = '/^[a-z][A-Za-z]*$/';

    protected function checkName(Node $node)
    {
        return preg_match(self::CAMEL_CASE_PATTERN, $node->name);
    }

    public function enterNode(Node $node)
    {
        $names = [
            Function_::class => 'Functions',
            ClassMethod::class => 'Methods',
            Variable::class => 'Variables',
            PropertyProperty::class => 'Properties'
        ];
        foreach ($names as $class => $text) {
            if ($node instanceof $class) {
                if (!$this->checkName($node)) {
                    $this->report($text.' MUST be declared in camelCase()', Report::WARNING_SEVERITY, $node);
                }
            }
        }
    }
}
