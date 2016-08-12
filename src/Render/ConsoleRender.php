<?php

namespace HexletPSRLinter\Render;

use HexletPSRLinter\Report;
use HexletPSRLinter\Message;

class ConsoleRender implements RenderInterface
{
    protected function renderReport(Report $report)
    {
        $results = [];
        if (!empty($report->getMessages())) {
            foreach ($report->getMessages() as $message) {
                $results[] = $this->renderMessage($message);
            }
            $results[] = '';
        }
        $results[] = $this->renderTotal($report);
        return implode(PHP_EOL, $results);
    }

    protected function renderMessage($message)
    {
        switch ($message['severity']) {
            case Report::WARNING_SEVERITY:
                $tag = 'comment';
                break;
            case Report::ERROR_SEVERITY:
                $tag = 'error';
                break;
            default:
                $tag = 'info';
                break;
        }
        $result = '<'.$tag.'>'.$message['severity'].':</'.$tag.'> '.$message['text'];
        if ($message['node'] !== null) {
            $result.= ' on line '.$message['node']->getLine();
        }
        return $result;
    }

    protected function renderTotal(Report $report)
    {
        $warnings = $report->getWarnings();
        $errors = $report->getErrors();
        if (empty($warnings) && empty($errors)) {
            return '<info>OK no problems detected.</info>'.PHP_EOL;
        } else {
            $warningsCount = count($warnings);
            $errorsCount = count($errors);
            $total = $warningsCount + $errorsCount;
            return
                '<error>'.
                    $total.' total problems detected('.$errorsCount.' errors, '.$warningsCount.
                    ' warnings).</error>'.PHP_EOL;
        }
    }

    public function render(array $reports)
    {
        $results = [];
        foreach ($reports as $filename => $report) {
            $results[] = $filename;
            $results[] = $this->renderReport($report);
        }
        return implode(PHP_EOL, $results);
    }
}
