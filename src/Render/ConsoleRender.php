<?php

namespace HexletPSRLinter\Render;

use HexletPSRLinter\Report;
use HexletPSRLinter\Linter;

class ConsoleRender implements RenderInterface
{
    const LINE_BREAK = "\r\n";

    protected function renderLinter(Linter $linter)
    {
        $result = $linter->getFileName().self::LINE_BREAK;
        foreach ($linter->getReports() as $report) {
            $result .= $this->renderReport($report);
        }
        return $result;
    }

    protected function renderReport(Report $report)
    {
        switch ($report->getSeverity()) {
            case Report::WARNING_SEVERITY:
                $tag = 'comment';
                break;
            default:
                $tag = 'error';
                break;
        }
        $result = '<'.$tag.'>'.$report->getSeverity().'</'.$tag.'> '.$report->getMessage();
        if ($report->getNode() !== null) {
            $result.= ' on line '.$report->getNode()->getLine();
        }
        return $result.self::LINE_BREAK;
    }

    protected function renderTotal($linter)
    {
        $warnings = $linter->getWarnings();
        $errors = $linter->getErrors();
        if (empty($warnings) && empty($errors)) {
            return '<info>OK no problems detected.</info>'.self::LINE_BREAK;
        } else {
            $warnings_count = count($warnings);
            $errors_count = count($errors);
            $total = $warnings_count + $errors_count;
            return
                '<error>'.
                    $total.' total problems detected('.$errors_count.' errors, '.$warnings_count.
                    ' warnings).</error>'.self::LINE_BREAK;
        }
    }

    public function render(array $linters)
    {
        $result = '';
        foreach ($linters as $linter) {
            $result .= $this->renderLinter($linter).self::LINE_BREAK;
            $result .= $this->renderTotal($linter);
        }
        return $result;
    }
}
