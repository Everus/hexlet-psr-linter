<?php

namespace HexletPSRLinter\Render;

use HexletPSRLinter\Report;
use HexletPSRLinter\Message;

class ConsoleRender implements RenderInterface
{
    const LINE_BREAK = "\r\n";

    protected function renderReport(Report $report)
    {
        $results = [];
        $results[] = $report->getName();
        foreach ($report->getMessages() as $message) {
            $results[] = $this->renderMessage($message);
        }
        return implode(self::LINE_BREAK, $results);
    }

    protected function renderMessage(Message $message)
    {
        switch ($message->getSeverity()) {
            case Message::WARNING_SEVERITY:
                $tag = 'comment';
                break;
            case Message::ERROR_SEVERITY:
                $tag = 'error';
                break;
            default:
                $tag = 'info';
                break;
        }
        $result = '<'.$tag.'>'.$message->getSeverity().'</'.$tag.'> '.$message->getMessage();
        if ($message->getNode() !== null) {
            $result.= ' on line '.$message->getNode()->getLine();
        }
        return $result;
    }

    protected function renderTotal($report)
    {
        $warnings = $report->getWarnings();
        $errors = $report->getErrors();
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

    public function render(array $reports)
    {
        $results = [];
        foreach ($reports as $report) {
            $results[] = $this->renderReport($report);
            $results[] = '';
            $results[] = $this->renderTotal($report);
        }
        return implode(self::LINE_BREAK, $results);
        return $result;
    }
}
