<?php

namespace HexletPSRLinter\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use HexletPSRLinter\Report;

class FuncNameRule extends RuleAbstract
{
    const CONTAIN_UNDERSCOPE_PATTERN = '/_+/i';
    const START_WITH_UPPERCASE_PATTERN = '/^[A-Z]/';
    const CONTAIN_ONLY_LATIN_LETTERS_PATTERN = '/[^A-Za-z]/';

    protected function getPatterns()
    {
        return [
            [
                'pattern' => self::CONTAIN_UNDERSCOPE_PATTERN,
                'message' => 'Method names SHOULD NOT contain a single underscore.',
                'severity' => Report::WARNING_SEVERITY
            ],
            [
                'pattern' => self::START_WITH_UPPERCASE_PATTERN,
                'message' => 'Methods MUST be declared in camelCase()',
                'severity' => Report::ERROR_SEVERITY
            ],
            [
                'pattern' => self::CONTAIN_ONLY_LATIN_LETTERS_PATTERN,
                'message' => 'Method names SHOULD contain only latin latters.',
                'severity' => Report::WARNING_SEVERITY
            ],
        ];
    }

    protected function checkName(Node $node)
    {
        foreach ($this->getPatterns() as $value) {
            if (preg_match($value['pattern'], $node->name)) {
                $this->report($value['message'], $value['severity'], $node);
            }
        }
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Function_ || $node instanceof ClassMethod) {
            $this->checkName($node);
        }
    }
}
