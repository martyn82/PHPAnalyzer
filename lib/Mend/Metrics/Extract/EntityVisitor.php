<?php
namespace Mend\Metrics\Extract;

use Mend\Metrics\Model\ModelVisitor;
use Mend\Parser\Node\Node;

class EntityVisitor extends ModelVisitor {
	/**
	 * Initializes the result.
	 */
	protected function init() {
		$this->result = array();
	}

	/**
	 * Adds the given node to result.
	 *
	 * @param Node $node
	 */
	public function addResult( Node $node ) {
		$this->result[] = $node;
	}
}
