<?php

declare(strict_types=1);

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

use function sprintf;

class ArrayAppend extends FunctionNode
{
    /** @var Node */
    private $expr1;

    /** @var InputParameter */
    private $expr2;

    public function parse(Parser $parser) : void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr2 = $parser->InputParameter();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'array_append(%s, %s)',
            $this->expr1->dispatch($sqlWalker),
            $sqlWalker->walkInputParameter($this->expr2)
        );
    }
}
