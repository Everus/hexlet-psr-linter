<?php

namespace HexletPSRLinter;

use PhpParser\Node;

class Report
{
    const WARNING_SEVERITY = 'Warning';
    const ERROR_SEVERITY = 'Error';
    protected $node;
    protected $message;
    protected $severity;


    /**
    * @param string $severity
    * @param string $message
    * @param null|Node $node
    */
    public function __construct($message, $severity = self::ERROR_SEVERITY, $node = null)
    {
        $this->setSeverity($severity)
            ->setNode($node)
            ->setMessage($message);
    }

    /**
    * @param string $severity
    * @return Message
    */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
    * @return string
    */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
    * @param null|Node $node
    * @return Message
    */
    public function setNode($node)
    {
        $this->node =$node;
        return $this;
    }

    /**
    * @return null|Node
    */
    public function getNode()
    {
        return $this->node;
    }

    /**
    * @param string $message
    * @return Message
    */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
    * @return string
    */
    public function getMessage()
    {
        return $this->message;
    }
}
