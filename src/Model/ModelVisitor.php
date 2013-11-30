<?php
namespace Model;

class ModelVisitor implements \PHPParser_NodeVisitor {
	private $nodeTypes = array();
	private $result = array();
	
	public function __construct( array $nodeTypes ) {
		$this->nodeTypes = $nodeTypes;
		$this->result = array();
	}
	
	public function getResult() {
		return $this->result;
	}
	
	private function addResult( \PHPParser_Node $node ) {
		$this->result[] = $node;
	}
	
	public function enterNode( \PHPParser_Node $node ) {
		$nodeClass = get_class( $node );
		
		if ( in_array( $nodeClass, $this->nodeTypes ) ) {
			$this->addResult( $node );
		}
	}

	public function beforeTraverse( array $nodes ) { /* noop */ }
	public function leaveNode( \PHPParser_Node $node ) { /* noop */ }
	public function afterTraverse( array $nodes ) { /* noop */ }
}