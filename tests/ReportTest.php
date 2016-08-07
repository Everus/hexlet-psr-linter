<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;
use PhpParser\NodeAbstract;

class ReportTest extends TestCase
{
    public function testConstructor()
    {
        $report = new Report('test');
        $this->assertEquals('test', $report->getMessage());
        $this->assertEquals(Report::ERROR_SEVERITY, $report->getSeverity());
        $this->assertEquals(null, $report->getNode());
    }

    public function testMessageSetter()
    {
        $report = new Report('test_message');
        $this->assertEquals('test_message', $report->getMessage());
        $report->setMessage('different_mess');
        $this->assertEquals('different_mess', $report->getMessage());
    }

    public function testSeveritySetter()
    {
        $report = new Report('test_message', REPORT::WARNING_SEVERITY);
        $this->assertEquals(REPORT::WARNING_SEVERITY, $report->getSeverity());
        $report->setSeverity(Report::ERROR_SEVERITY);
        $this->assertEquals(Report::ERROR_SEVERITY, $report->getSeverity());
    }

    public function testNodeSetter()
    {
        $report = new Report('test_message', REPORT::WARNING_SEVERITY);
        $this->assertEquals(null, $report->getNode());
        $node = $this->getMockBuilder(NodeAbstract::class)
            ->disableOriginalConstructor()
            ->getMock();
        $report->setNode($node);
        $this->assertEquals($node, $report->getNode());
    }
}
