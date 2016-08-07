<?php

namespace HexletPSRLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\Error;

class Linter
{
    protected $reports;
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
        $this->reports = [];
    }

    public function getReports()
    {
        return $this->reports;
    }

    public function addReport(Report $report)
    {
        $this->reports[] = $report;
        return $this;
    }

    public function getReportsBySeverity($severity)
    {
        return array_filter($this->reports, function ($report) use ($severity) {
            return $report->getSeverity() === $severity;
        });
    }

    public function getWarnings()
    {
        return $this->getReportsBySeverity(Report::WARNING_SEVERITY);
    }

    public function getErrors()
    {
        return $this->getReportsBySeverity(Report::ERROR_SEVERITY);
    }

    public function getRules()
    {
        return [
            new Rule\FuncNameRule($this),
            new Rule\VariableNameRule($this)
        ];
    }

    protected function addVisitors(NodeTraverser $traverser)
    {
        foreach ($this->getRules() as $rule) {
            $traverser->addVisitor($rule);
        }
    }

    public function getFileName()
    {
        return $this->file->getFileName();
    }

    public function getCode()
    {
        return $this->file->getContent();
    }

    public function lint()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $this->addVisitors($traverser);
        try {
            $stmts = $parser->parse($this->getCode());
            $stmts = $traverser->traverse($stmts);
        } catch (Error $e) {
            $this->addReport(Report::ERROR_SEVERITY, 'Parse Error: '.$e->getMessage());
        }
        return $this;
    }
}
