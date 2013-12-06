<?php
namespace Parser\AST;

use Parser\AST\ParserNode;

class PHPParserNode extends ParserNode {
	private $node;

	public function __construct( \PHPParser_Node $node ) {
		$this->node = $node;
	}

	public function getNode() {
		return $this->node;
	}
}