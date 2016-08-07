<?php

namespace HexletPSRLinter\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\PropertyProperty;
use HexletPSRLinter\Message;

class VariableNameRule extends RuleAbstract
{
    protected $styles = [
        '/^[A-Z][A-Za-z]*$/',
        '/^[a-z][A-Za-z]*$/',
        '/^[a-z][_a-z]*$/'
    ];

    protected function getRules()
    {
        return [
            [
                'pattern' => '/^[_]/',
                'message' => 'Property names SHOULD NOT be prefixed with a single underscore to indicate protected or private visibility.',
                'severity' => Message::WARNING_SEVERITY
            ],
            [
                'pattern' => '/[^A-Za-z_]/',
                'message' => 'Property names MUST contain only latin latters or _ underscope.',
                'severity' => Message::ERROR_SEVERITY
            ],
            [
                'pattern' => '/([A-Z]+\w*_)|(_\w*[A-Z]+)/',
                'message' => 'Property names MUST be declared in $StudlyCaps, $camelCase, or $under_score.',
                'severity' => Message::ERROR_SEVERITY
            ],
        ];
    }

    protected function checkNamingStyle(Node $node)
    {
        $passedStyles = array_filter($this->styles, function ($style) use ($node) {
            return preg_match($style, $node->name);
        });
        if(empty($passedStyles)) {
            $this->report(
                'Whatever naming convention is used SHOULD be applied consistently within a reasonable scope.',
                Report::WARNING_SEVERITY,
                $node
            );
        } else {
            $this->styles = $passedStyles;
        }
    }

    protected function checkName(Node $node)
    {
        $result = true;
        foreach ($this->getRules() as $rule) {
            if(preg_match($rule['pattern'], $node->name)) {
                $this->report($rule['message'], $rule['severity'], $node);
                $result = false;
            }
        }
        return $result;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Variable || $node instanceof PropertyProperty) {
            if($this->checkName($node)) {
                $this->checkNamingStyle($node);
            }
        }
    }
}
