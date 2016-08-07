<?php

namespace HexletPSRLinter\Rule;

use HexletPSRLinter\Linter;
use PhpParser\NodeVisitor;

interface RuleInterface extends NodeVisitor
{
    public function __construct(Linter $linter);
}
