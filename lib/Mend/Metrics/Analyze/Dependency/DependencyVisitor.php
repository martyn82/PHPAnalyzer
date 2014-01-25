<?php
namespace Mend\Metrics\Analyze\Dependency;

use Mend\Metrics\Model\ModelVisitor;
use Mend\Parser\Node\Node;
use Mend\Parser\Node\NodeFilter;

class DependencyVisitor extends ModelVisitor {
	/**
	 * @see ModelVisitor::init()
	 */
	protected function init() {
		$this->result = array();
	}

	/**
	 * @see ModelVisitor::addResult()
	 */
	public function addResult( Node $node ) {
		$this->result[] = $node;
	}

	/**
	 * @see ModelVisitor::getResult()
	 */
	public function getResult() {
		$nodeFilter = new NodeFilter();
		return $nodeFilter->getUnique( $this->result );
	}
}