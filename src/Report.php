<?php

namespace HexletPSRLinter;

use PhpParser\Node;

class Report
{
    const WARNING_SEVERITY = 'Warning';
    const ERROR_SEVERITY = 'Error';
    const INFO_SEVERITY = 'Info';
    /**
     * @var array Array of messages
     */
    protected $messages;

    public function __construct()
    {
        $this->messages = [];
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getMessagesBySeverity($severity)
    {
        return array_filter($this->messages, function ($message) use ($severity) {
            return $message['severity'] === $severity;
        });
    }

    public function getWarnings()
    {
        return $this->getMessagesBySeverity(Report::WARNING_SEVERITY);
    }

    public function getErrors()
    {
        return $this->getMessagesBySeverity(Report::ERROR_SEVERITY);
    }

    /**
     * @return array Array of data
     */
    public function toArray()
    {
        return ['messages' => $this->messages];
    }


    /**
     * @param string $text Text message from Rule
     * @param string $severity Severity of this message
     * @param null|Node $node
     * @return $this
     */
    public function report($text, $severity = self::ERROR_SEVERITY, $node = null)
    {
        $this->messages[] = [
            'text' => $text,
            'severity' => $severity,
            'node' => $node
        ];
        return $this;
    }
}
