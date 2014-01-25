<?php
namespace Mend\Metrics\Model\Code;

use Mend\Parser\Node\Node;

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