<?php

namespace Brick\Doctrine\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * Provides DQL support for MySQL's FullText search.
 *
 * How to register it:
 *
 *     $config->addCustomNumericFunction('Match', \Brick\Doctrine\Functions\MatchAgainstFunction::class);
 *
 * How to use it:
 *
 *     MATCH(a.foo) AGAINST(?)
 *     MATCH(a.foo, b.bar) AGAINST(:text 'IN BOOLEAN MODE')
 *
 * Note the quotes around the optional mode.
 */
class MatchAgainstFunction extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\PathExpression[]
     */
    private $match;

    /**
     * @var mixed
     */
    private $against;

    /**
     * @var string|null
     */
    private $mode;

    /**
     * @inheritdoc
     */
    public function parse(Parser $parser)
    {
        $this->match = [];
        $this->mode = null;

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();

        for (;;) {
            $this->match[] = $parser->StateFieldPathExpression();

            if (! $lexer->isNextToken(Lexer::T_COMMA)) {
                break;
            }

            $parser->match(Lexer::T_COMMA);
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);

        if (strtoupper($lexer->lookahead['value']) !== 'AGAINST') {
            $parser->syntaxError('AGAINST');
        }

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->against = $parser->StringPrimary();

        if ($lexer->isNextToken(Lexer::T_STRING)) {
            $parser->match(Lexer::T_STRING);
            $this->mode = $lexer->token['value'];
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @inheritdoc
     */
    public function getSql(SqlWalker $walker)
    {
        $matches = [];

        foreach ($this->match as $match) {
            $matches[] = $match->dispatch($walker);
        }

        $against = $walker->walkStringPrimary($this->against);

        if ($this->mode !== null) {
            $against .= ' ' . $this->mode;
        }

        return sprintf('MATCH (%s) AGAINST (%s)', implode(', ', $matches), $against);
    }
}
