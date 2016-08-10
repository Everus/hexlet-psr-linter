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
