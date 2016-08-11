<?php

namespace HexletPSRLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\Error;

class Linter
{
    protected function generateRules(Report $report)
    {
        return [
            new Rule\NameRule($report),
            new Rule\SideEffectRule($report)
        ];
    }

    public function lint($code)
    {
        $report = new Report();
        $rules = $this->generateRules($report);
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        foreach ($rules as $rule) {
            $traverser->addVisitor($rule);
        }
        try {
            $stmts = $parser->parse($code);
            $stmts = $traverser->traverse($stmts);
        } catch (Error $e) {
            $report->report('Parse Error: '.$e->getMessage());
        }
        return $report;
    }
}
