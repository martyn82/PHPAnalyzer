<?php
namespace Mend\Parser\Node;

abstract class Node {
	/**
	 * @var mixed
	 */
	private $innerNode;

	/**
	 * @var boolean
	 */
	protected $isEmpty;

	/**
	 * Sets the inner node.
	 *
	 * @param mixed $node
	 */
	protected function setInnerNode( $node ) {
		$this->innerNode = $node;
	}

	/**
	 * Retrieves the inner node.
	 *
	 * @return mixed
	 */
	public function getInnerNode() {
		return $this->innerNode;
	}

	/**
	 * Retrieves the package separator character.
	 *
	 * @return string
	 */
	abstract public function getPackageSeparator();

	/**
	 * Retrieves the node name.
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Retrieves the start line of the node.
	 *
	 * @return integer
	 */
	abstract public function getStartLine();

	/**
	 * Retrieves the end line of the node.
	 *
	 * @return integer
	 */
	abstract public function getEndLine();
}