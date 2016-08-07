<?php

namespace HexletPSRLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\Error;

class Report
{
    protected $messages;
    protected $name = '';

    public function __construct()
    {
        $this->messages = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
        return $this;
    }

    public function report($message, $severity = Message::ERROR_SEVERITY, $node = null)
    {
        return $this->addMessage(new Message($message, $severity, $node));
    }

    public function getMessagesBySeverity($severity)
    {
        return array_filter($this->messages, function ($message) use ($severity) {
            return $message->getSeverity() === $severity;
        });
    }

    public function getWarnings()
    {
        return $this->getMessagesBySeverity(Message::WARNING_SEVERITY);
    }

    public function getErrors()
    {
        return $this->getMessagesBySeverity(Message::ERROR_SEVERITY);
    }
}
