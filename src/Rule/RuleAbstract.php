<?php

namespace HexletPSRLinter\Rule;

use HexletPSRLinter\Report;
use HexletPSRLinter\Message;
use PhpParser\Node;

abstract class RuleAbstract implements RuleInterface
{
    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    protected function report($message, $severity, $node = null)
    {
        $this->report->report($message, $severity, $node);
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
