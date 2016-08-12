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

use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;

class SideEffectRule extends RuleAbstract
{
    /**
     * @var int Depth inside declaration
     */
    protected $depth = 0;

    /**
     * @var array
     */
    protected $declarations = [
        Interface_::class,
        Class_::class,
        Function_::class
    ];

    /**
     * @var array
     */
    protected $neutral = [
        Namespace_::class,
        Use_::class,
        UseUse::class,
        Name::class
    ];

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
            return ($node instanceof $class) ? true : $acc;
        }, false);
    }

    /**
     * @param Node $node
     * @return mixed
     */
    protected function isNeutral(Node $node)
    {
        return array_reduce($this->neutral, function ($acc, $class) use ($node) {
            return ($node instanceof $class) ? true : $acc;
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
        if ($this->isSideEffect($node) && $this->depth == 0) {
            if ($this->depth > 0) {
                return null;
            }
            if (!is_null($this->firstDeclaration)) {
                $line = $this->firstDeclaration->getLine();
                $text = "File SHOULD declare new symbols or execute logic with side ".
                    "effects, but SHOULD NOT do both first declaration has been detected on line $line, ".
                    "after this side effect instruction has been detected";
                $this->report($text, Report::ERROR_SEVERITY, $node);
            }
            $this->firstSideEffect = is_null($this->firstSideEffect) ? $node : $this->firstSideEffect;
            return null;
        }
        if ($this->isDeclaration($node)) {
            if (!is_null($this->firstSideEffect)) {
                $line = $this->firstSideEffect->getLine();
                $text = "File SHOULD declare new symbols or execute logic with side ".
                    "effects, but SHOULD NOT do both. First side effect has been detected on line $line, ".
                    "after this declaration of new symbol has been detected";
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
