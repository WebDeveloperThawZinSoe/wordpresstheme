<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhpRBE\ScssPhp\Ast\Sass\Expression;

use ScssPhpRBE\ScssPhp\Ast\Sass\Expression;
use ScssPhpRBE\ScssPhp\SourceSpan\FileSpan;
use ScssPhpRBE\ScssPhp\Visitor\ExpressionVisitor;

/**
 * A boolean literal, `true` or `false`.
 *
 * @internal
 */
final class BooleanExpression implements Expression
{
    /**
     * @var bool
     * @readonly
     */
    private $value;

    /**
     * @var FileSpan
     * @readonly
     */
    private $span;

    public function __construct(bool $value, FileSpan $span)
    {
        $this->value = $value;
        $this->span = $span;
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accepts(ExpressionVisitor $visitor)
    {
        return $visitor->visitBooleanExpression($this);
    }
}
