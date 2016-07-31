<?php

namespace HexletPSRLinter;

use PhpParser\Node;

interface Visitor
{
  public function visit(Node $node);
}
