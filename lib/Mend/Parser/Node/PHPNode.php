<?php
namespace Mend\Parser\Node;

class PHPNode extends Node {
	/**
	 * Constructs a new Node instance.
	 *
	 * @param \PHPParser_Node $inner
	 */
	public function __construct( \PHPParser_Node $inner ) {
		$this->setInnerNode( $inner );
	}

	/**
	 * @see Node::getName()
	 */
	public function getName() {
		return $this->getInnerNode()->name;
	}

	/**
	 * @see Node::getStartLine()
	 */
	public function getStartLine() {
		return $this->getInnerNode()->getAttribute( 'startLine' );
	}

	/**
	 * @see Node::getEndLine()
	 */
	public function getEndLine() {
		return $this->getInnerNode()->getAttribute( 'endLine' );
	}

	/**
	 * @see Node::getPackageSeparator()
	 */
	public function getPackageSeparator() {
		return '\\';
	}
}