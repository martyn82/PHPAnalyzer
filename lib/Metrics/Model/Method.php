<?php
namespace Metrics\Model;

use \Metrics\Model\Location;
use \Metrics\Model\ComplexityModel;
use \Metrics\Model\UnitSizeModel;

class Method {
	/**
	 * @var \PHPParser_Node
	 */
	private $node;

	/**
	 * @var \Metrics\Model\Location
	 */
	private $location;

	/**
	 * @var \Metrics\Model\ComplexityModel
	 */
	private $complexity;

	/**
	 * @var \Metrics\Model\UnitSizeModel
	 */
	private $unitSize;

	/**
	 * Constructs a new Method model.
	 *
	 * @param \PHPParser_Node $node
	 * @param \Metrics\Model\Location $location
	 */
	public function __construct( \PHPParser_Node $node, Location $location ) {
		$this->node = $node;
		$this->location = $location;
	}

	/**
	 * @return \Metrics\Model\Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @return \PHPParser_Node
	 */
	public function getNode() {
		return $this->node;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->node->name;
	}

	/**
	 * Gets/Sets the UnitSize model.
	 *
	 * @param \Metrics\Model\UnitSizeModel $value
	 *
	 * @return \Metrics\Model\UnitSizeModel
	 */
	public function unitSize( UnitSizeModel $value = null ) {
		if ( !is_null( $value ) ) {
			$this->unitSize = $value;
		}
		return $this->unitSize;
	}

	/**
	 * Gets/Sets the complexity model.
	 *
	 * @param \Metrics\Model\ComplexityModel $value
	 *
	 * @return \Metrics\Model\ComplexityModel
	 */
	public function complexity( ComplexityModel $value = null ) {
		if ( !is_null( $value ) ) {
			$this->complexity = $value;
		}
		return $this->complexity;
	}
}