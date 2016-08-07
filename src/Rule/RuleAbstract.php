<?php

namespace HexletPSRLinter\Rule;

use HexletPSRLinter\Report;
use HexletPSRLinter\Linter;
use PhpParser\Node;

abstract class RuleAbstract implements RuleInterface
{
    protected $linter;

    public function __construct(Linter $linter)
    {
        $this->linter = $linter;
    }

    protected function report($message, $severity, $node = null)
    {
        $this->linter->addReport(new Report($message, $severity, $node));
    }

    public function beforeTraverse(array $nodes)
    {
    }

    public function enterNode(Node $node)
    {
    }

    public function leaveNode(Node $node)
    {
    }

    public function afterTraverse(array $nodes)
    {
    }
}
