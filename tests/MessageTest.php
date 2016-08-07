<?php

namespace HexletPSRLinter;

use PHPUnit\Framework\TestCase;
use PhpParser\NodeAbstract;

class MessageTest extends TestCase
{
    public function testConstructor()
    {
        $message = new Message('test');
        $this->assertEquals('test', $message->getMessage());
        $this->assertEquals(Message::ERROR_SEVERITY, $message->getSeverity());
        $this->assertEquals(null, $message->getNode());
    }

    public function testMessageSetter()
    {
        $message = new Message('test_message');
        $this->assertEquals('test_message', $message->getMessage());
        $message->setMessage('different_mess');
        $this->assertEquals('different_mess', $message->getMessage());
    }

    public function testSeveritySetter()
    {
        $message = new Message('test_message', Message::WARNING_SEVERITY);
        $this->assertEquals(Message::WARNING_SEVERITY, $message->getSeverity());
        $message->setSeverity(Message::ERROR_SEVERITY);
        $this->assertEquals(Message::ERROR_SEVERITY, $message->getSeverity());
    }

    public function testNodeSetter()
    {
        $message = new Message('test_message', Message::WARNING_SEVERITY);
        $this->assertEquals(null, $message->getNode());
        $node = $this->getMockBuilder(NodeAbstract::class)
            ->disableOriginalConstructor()
            ->getMock();
        $message->setNode($node);
        $this->assertEquals($node, $message->getNode());
    }
}
