<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class FileTest extends TestCase
{
    public function setUp()
    {
        vfsStream::setup('test');
    }

    public function testFileConstructor()
    {
        $file = new File('test_name');
        $this->assertEquals('test_name', $file->getFileName());
    }

    public function testContentReading()
    {
        $test_file = vfsStream::url('test/test.txt');
        file_put_contents($test_file, 'Test content');
        $file = new File($test_file);
        $this->assertEquals($file->getContent(), 'Test content');
    }

    public function testSave()
    {
        $test_file = vfsStream::url('test/test.txt');
        file_put_contents($test_file, 'Test content');
        $file = new File($test_file);
        $file->setContent('new content')->save();
        $this->assertEquals('new content', file_get_contents($test_file));
    }
}
