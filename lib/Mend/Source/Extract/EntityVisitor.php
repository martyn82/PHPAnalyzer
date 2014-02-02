<?php
namespace Mend\Source\Extract;

use Mend\Parser\Node\Node;
use Mend\Source\Code\ModelVisitor;

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
