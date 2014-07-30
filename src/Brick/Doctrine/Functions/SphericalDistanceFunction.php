<?php

namespace Brick\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

/**
 * Distance calculation between two points on the Earth, approximated as a sphere.
 */
class SphericalDistanceFunction extends FunctionNode
{
    /**
     * The quadratic mean approximation of the average
     * great-circle circumference of the Earth, in meters.
     *
     * @const float
     */
    const EARTH_RADIUS = 6372797.5559;

    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    private $arg1;

    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    private $arg2;

    /**
     * @param string $value
     *
     * @return string
     */
    private static function x($value)
    {
        return sprintf('RADIANS(X(%s))', $value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private static function y($value)
    {
        return sprintf('RADIANS(Y(%s))', $value);
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $s
     *
     * @return string
     */
    private function x1(SqlWalker $s)
    {
        return self::x($this->arg1->dispatch($s));
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $s
     *
     * @return string
     */
    private function x2(SqlWalker $s)
    {
        return self::x($this->arg2->dispatch($s));
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $s
     *
     * @return string
     */
    private function y1(SqlWalker $s)
    {
        return self::y($this->arg1->dispatch($s));
    }

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $s
     *
     * @return string
     */
    private function y2(SqlWalker $s)
    {
        return self::y($this->arg2->dispatch($s));
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $s)
    {
        $formula = '%s * ACOS(SIN(%s) * SIN(%s) + COS(%s) * COS(%s) * COS(%s - %s))';

        return sprintf(
            $formula,
            self::EARTH_RADIUS,
            $this->y1($s),
            $this->y2($s),
            $this->y1($s),
            $this->y2($s),
            $this->x2($s),
            $this->x1($s)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->arg1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->arg2 = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
