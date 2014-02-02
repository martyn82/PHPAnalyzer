<?php
namespace Mend\Metrics\Complexity;

use Mend\Parser\Node\Node;
use Mend\Source\Code\ModelVisitor;

class ComplexityVisitor extends ModelVisitor {
	/**
	 * Initializes the visitor.
	 */
	protected function init() {
		$this->result = 0;
	}

	/**
	 * Adds a node to the result.
	 *
	 * @param Node $node
	 */
	protected function addResult( Node $node ) {
		$this->result++;
	}
}
