<?php
namespace Parser;

use Parser\ParserNodeVisitor;
use Parser\AST\ParserNodeArray;

class PHPParserNodeTraverser extends ParserNodeTraverser {
	private $traverser;

	public function __construct() {
		$this->traverser = new \PHPParser_NodeTraverser();
	}

	public function addVisitor( ParserNodeVisitor $visitor ) {
		$this->traverser->addVisitor( $visitor );
	}

	public function traverse( ParserNodeArray $nodes ) {
		$this->traverser->traverse( (array) $nodes );
	}
}