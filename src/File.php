<?php

namespace HexletPSRLinter;

class File
{
    protected $filename;
    protected $content = null;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getFileName()
    {
        return $this->filename;
    }

    public function getContent()
    {
        if ($this->content === null) {
            $this->content = file_get_contents($this->filename);
        }
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function save()
    {
        file_put_contents($this->filename, $this->content);
        return $this;
    }
}
