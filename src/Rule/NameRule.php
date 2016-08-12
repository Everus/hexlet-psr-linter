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

    protected function isMagicMethod(Node $node)
    {
        if ($node instanceof ClassMethod) {
            $magicMethods = [
                '__construct',
                '__destruct',
                '__call',
                '__callStatic',
                '__get',
                '__set',
                '__isset',
                '__unset',
                '__sleep',
                '__wakeup',
                '__toString',
                '__invoke',
                '__set_state',
                '__clone',
                '__debugInfo'
            ];
            return array_reduce($magicMethods, function ($acc, $name) use ($node) {
                return $acc || ($name == $node->name);
            }, false);
        }
        return false;
    }

    protected function checkName(Node $node)
    {
        if ($this->isMagicMethod($node)) {
            return true;
        }
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
