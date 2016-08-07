<?php

namespace HexletPSRLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\Error;

class Linter
{
    protected function generateRules($report)
    {
        return [
            new Rule\FuncNameRule($report),
            new Rule\VariableNameRule($report)
        ];
    }

    protected function addVisitors(NodeTraverser $traverser)
    {
        foreach ($this->getRules() as $rule) {
            $traverser->addVisitor($rule);
        }
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
