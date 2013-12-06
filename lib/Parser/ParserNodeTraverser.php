<?php
namespace Parser;

use Parser\ParserNodeVisitor;
use Parser\AST\ParserNodeArray;

abstract class ParserNodeTraverser {
	abstract public function addVisitor( ParserNodeVisitor $visitor );
	abstract public function traverse( ParserNodeArray $nodes );
}