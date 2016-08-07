<?php

namespace HexletPSRLinter\Render;

use HexletPSRLinter\LinterObserverAbstract;

interface RenderInterface
{
    public function render(array $linters);
}
