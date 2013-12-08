<?php
namespace Mend\Metrics\Model;

abstract class ModelVisitor implements \PHPParser_NodeVisitor {
	/**
	 * @var array
	 */
	private $nodeTypes = array();

	/**
	 * @var mixed
	 */
	protected $result;

	/**
	 * Constructs a new ModelVisitor.
	 *
	 * @param array $nodeTypes
	 */
	public function __construct( array $nodeTypes ) {
		$this->nodeTypes = $nodeTypes;
		$this->init();
	}

	/**
	 * Retrieves the visitor result.
	 *
	 * @return mixed
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Initializer template method.
	 */
	abstract protected function init();

	/**
	 * Notifies to record this node as a result.
	 *
	 * @param \PHPParser_Node $node
	 */
	abstract protected function addResult( \PHPParser_Node $node );

	/**
	 * Visits the given node.
	 *
	 * @param \PHPParser_Node $node
	 */
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