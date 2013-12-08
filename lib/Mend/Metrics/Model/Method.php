<?php
namespace Mend\Metrics\Model;

use \Mend\Metrics\Model\Location;
use \Mend\Metrics\Model\ComplexityModel;
use \Mend\Metrics\Model\UnitSizeModel;
use \Mend\Metrics\Arrayable;

class Method implements Arrayable {
	/**
	 * @var \PHPParser_Node
	 */
	private $node;

	/**
	 * @var Location
	 */
	private $location;

	/**
	 * @var ComplexityModel
	 */
	private $complexity;

	/**
	 * @var UnitSizeModel
	 */
	private $unitSize;

	/**
	 * Constructs a new Method model.
	 *
	 * @param \PHPParser_Node $node
	 * @param Location $location
	 */
	public function __construct( \PHPParser_Node $node, Location $location ) {
		$this->node = $node;
		$this->location = $location;
	}

	/**
	 * @return Location
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
	 * @param UnitSizeModel $value
	 *
	 * @return UnitSizeModel
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
	 * @param ComplexityModel $value
	 *
	 * @return ComplexityModel
	 */
	public function complexity( ComplexityModel $value = null ) {
		if ( !is_null( $value ) ) {
			$this->complexity = $value;
		}
		return $this->complexity;
	}

	/**
	 * Converts this object to array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'name' => $this->getName(),
			'unitSize' => $this->unitSize->toArray(),
			'complexity' => $this->complexity->toArray(),
			'location' => $this->location->toArray()
		);
	}
}