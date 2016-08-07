<?php

namespace HexletPSRLinter\Rule;

use HexletPSRLinter\Report;
use PhpParser\NodeVisitor;

interface RuleInterface extends NodeVisitor
{
    public function __construct(Report $linter);
}
