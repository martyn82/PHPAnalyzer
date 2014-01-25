<?php
namespace Mend\Metrics\Analyze\Complexity;

use Mend\Metrics\Model\ModelVisitor;
use Mend\Parser\Node\Node;

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
