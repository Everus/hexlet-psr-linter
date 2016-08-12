<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;
use PhpParser\NodeAbstract;

class ReportTest extends TestCase
{
    public function testMessageStorage()
    {
        $report = new Report();

        $report->report('Test error', Report::ERROR_SEVERITY);
        $report->report('Test warning 1', Report::WARNING_SEVERITY);
        $report->report('Test warning 2', Report::WARNING_SEVERITY);

        $this->assertEquals([
            'messages' => [
                [
                    'text' => 'Test error',
                    'severity' => Report::ERROR_SEVERITY,
                    'node' => null
                ],
                [
                    'text' => 'Test warning 1',
                    'severity' => Report::WARNING_SEVERITY,
                    'node' => null
                ],
                [
                    'text' => 'Test warning 2',
                    'severity' => Report::WARNING_SEVERITY,
                    'node' => null
                ],
            ]
        ], $report->toArray());
    }

    public function testMessageStorageEmpty()
    {
        $report = new Report();
        $this->assertEquals(['messages'=> []], $report->toArray());
    }
}
