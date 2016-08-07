<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;

class LinterTest extends TestCase
{
    protected function getFileMock()
    {
        $file = $this->getMockBuilder(File::class)
            ->setMethods(['getContent', 'getFileName'])
            ->disableOriginalConstructor()
            ->getMock();
        return $file;
    }

    public function testMessageStorage()
    {
        $file = $this->getFileMock();
        $linter = new Linter($file);
        $error = new Report('Test error', Report::ERROR_SEVERITY);
        $warning1 = new Report('Test warning 1', Report::WARNING_SEVERITY);
        $warning2 = new Report('Test warning 2', Report::WARNING_SEVERITY);

        $linter->addReport($error);
        $linter->addReport($warning1);
        $linter->addReport($warning2);

        $messages = $linter->getReports();
        $errors = $linter->getErrors();
        $warnings = $linter->getWarnings();

        $this->assertCount(3, $messages);
        $this->assertCount(1, $errors);
        $this->assertCount(2, $warnings);

        $this->assertContains($error, $errors);
        $this->assertContains($warning1, $warnings);
        $this->assertContains($warning2, $warnings);
    }

    public function testMessageStorageEmptyErrors()
    {
        $file = $this->getFileMock();
        $linter = new Linter($file);
        $warning1 = new Report('Test warning 1', Report::WARNING_SEVERITY);
        $linter->addReport($warning1);
        $this->assertEmpty($linter->getErrors());
    }

    public function testMessageStorageWarningsErrors()
    {
        $file = $this->getFileMock();
        $linter = new Linter($file);
        $warning1 = new Report('Test warning 1', Report::ERROR_SEVERITY);
        $linter->addReport($warning1);
        $this->assertEmpty($linter->getWarnings());
    }

    public function testMessageStorageEmpty()
    {
        $file = $this->getFileMock();
        $linter = new Linter($file);
        $this->assertEmpty($linter->getReports());
        $this->assertEmpty($linter->getErrors());
        $this->assertEmpty($linter->getWarnings());
    }

    public function testConstructor()
    {
        $file = $this->getFileMock();
        $file->expects($this->once())
            ->method('getContent')
            ->willReturn('test_code');
        $file->expects($this->once())
            ->method('getFileName')
            ->willReturn('test_filename');

        $linter = new Linter($file);
        $this->assertEquals('test_filename', $linter->getFileName());
        $this->assertEquals('test_code', $linter->getCode());
    }
}
