<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;
use PhpParser\NodeAbstract;

class ReportTest extends TestCase
{
    public function testMessageStorage()
    {
        $report = new Report();
        $error = new Message('Test error', Message::ERROR_SEVERITY);
        $warning1 = new Message('Test warning 1', Message::WARNING_SEVERITY);
        $warning2 = new Message('Test warning 2', Message::WARNING_SEVERITY);

        $report->addMessage($error);
        $report->addMessage($warning1);
        $report->addMessage($warning2);

        $messages = $report->getMessages();
        $errors = $report->getErrors();
        $warnings = $report->getWarnings();

        $this->assertCount(3, $messages);
        $this->assertCount(1, $errors);
        $this->assertCount(2, $warnings);

        $this->assertContains($error, $errors);
        $this->assertContains($warning1, $warnings);
        $this->assertContains($warning2, $warnings);
    }

    public function testMessageStorageEmptyErrors()
    {
        $report = new Report();
        $warning1 = new Message('Test warning 1', Message::WARNING_SEVERITY);
        $report->addMessage($warning1);
        $this->assertEmpty($report->getErrors());
    }

    public function testMessageStorageWarningsErrors()
    {
        $report = new Report();
        $warning1 = new Message('Test warning 1', Message::ERROR_SEVERITY);
        $report->addMessage($warning1);
        $this->assertEmpty($report->getWarnings());
    }

    public function testMessageStorageEmpty()
    {
        $report = new Report();
        $this->assertEmpty($report->getMessages());
        $this->assertEmpty($report->getErrors());
        $this->assertEmpty($report->getWarnings());
    }
}
