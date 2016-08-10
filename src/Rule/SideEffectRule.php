<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 10.08.16
 * Time: 10:47
 */

namespace HexletPSRLinter\Rule;

use PhpParser\Node;
use HexletPSRLinter\Report;

class SideEffectRule extends RuleAbstract
{
    /**
     * @var int Depth inside declaration
     */
    protected $depth = 0;

    /**
     * @var array
     */
    protected $declarations = [];

    /**
     * @var array
     */
    protected $neutral = [];

    /**
     * @var null|Node
     */
    protected $firstDeclaration = null;

    /**
     * @var null|Node
     */
    protected $firstSideEffect = null;

    /**
     * @var bool
     */
    protected $reported = false;

    /**
     * @param $node
     * @return bool
     */
    protected function isDeclaration(Node $node)
    {
        return array_reduce($this->declarations, function ($acc, $class) use ($node) {
            return is_a($node, $class) ? true : $acc;
        }, false);
    }

    /**
     * @param Node $node
     * @return mixed
     */
    protected function isNeutral(Node $node)
    {
        return array_reduce($this->neutral, function ($acc, $class) use ($node) {
            return is_a($node, $class) ? true : $acc;
        }, false);
    }

    /**
     * @param Node $node
     * @return bool
     */
    protected function isSideEffect(Node $node)
    {
        return !($this->isNeutral($node) || $this->isDeclaration($node));
    }

    /**
     * @param string $text
     * @param string $severity
     * @param null|Node $node
     */
    protected function report($text, $severity, $node = null)
    {
        if (!$this->reported) {
            $this->reported = true;
            return parent::report($text, $severity, $node);
        }
    }

    public function enterNode(Node $node)
    {
        if ($this->reported) {
            return null;
        }
        if ($this->depth > 0) {
            if ($this->isDeclaration($node)) {
                $this->depth++;
                return null;
            }
        }
        if ($this->isSideEffect($node)) {
            if ($this->depth > 0) {
                return null;
            }
            if (!is_null($this->firstDeclaration)) {
                $line = $this->firstDeclaration->getLine();
                $text = <<<EOL
File SHOULD declare new symbols or execute logic with side
effects, but SHOULD NOT do both first declaration has been detected on line $line 
after this side effect instruction has been detected
EOL;
                $this->report($text, Report::ERROR_SEVERITY, $node);
            }
            $this->firstSideEffect = is_null($this->firstSideEffect) ? $node : $this->firstSideEffect;
            return null;
        }
        if ($this->isDeclaration($node)) {
            if (!is_null($this->firstSideEffect)) {
                $line = $this->firstSideEffect->getLine();
                $text = <<<EOL
File SHOULD declare new symbols or execute logic with side
effects, but SHOULD NOT do both. First side effect has been detected on line $line,
after this declaration of new symbol has been detected
EOL;
                $this->report($text, Report::ERROR_SEVERITY, $node);
            }
            $this->depth++;
            $this->firstDeclaration = is_null($this->firstDeclaration) ? $node : $this->firstDeclaration;
        }
    }

    public function leaveNode(Node $node)
    {
        if ($this->isDeclaration($node)) {
            $this->depth--;
        }
    }
}
