<?php
namespace Model;

use Parser\AST\ParserNodeArray;

class ModelTree {
	private $nodes;
	private $source;

	public function __construct( ParserNodeArray $nodes, $source ) {
		$this->nodes = $nodes;
		$this->source = $source;
	}

	public function getNodes() {
		return $this->nodes;
	}

	public function getSource() {
		return $this->source;
	}
}