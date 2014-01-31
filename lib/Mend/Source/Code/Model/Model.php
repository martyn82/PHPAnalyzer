<?php
namespace Mend\Source\Code\Model;

use Mend\Parser\Node\Node;
use Mend\Source\Code\Location\SourceUrl;

abstract class Model {
	/**
	 * @var Node
	 */
	private $node;

	/**
	 * @var SourceUrl
	 */
	private $sourceUrl;

	/**
	 * Constructs a new model.
	 *
	 * @param Node $node
	 * @param SourceUrl $url
	 */
	public function __construct( Node $node, SourceUrl $url ) {
		$this->node = $node;
		$this->sourceUrl = $url;

		$this->init();
	}

	/**
	 * Initializer.
	 */
	protected function init() {
		/* noop ; template method for sub-classes */
	}

	/**
	 * Determines whether the model has a node.
	 *
	 * @return boolean
	 */
	public function hasNode() {
		return !is_null( $this->node );
	}

	/**
	 * Retrieves the AST node.
	 *
	 * @return Node
	 */
	public function getNode() {
		return $this->node;
	}

	/**
	 * Retrieves the location of the model.
	 *
	 * @return SourceUrl
	 */
	public function getSourceUrl() {
		return $this->sourceUrl;
	}

	/**
	 * Retrieves the model name.
	 *
	 * @return string
	 */
	public function getName() {
		return (string) $this->getNode()->getName();
	}
}