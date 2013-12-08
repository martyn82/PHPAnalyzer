<?php
namespace Metrics\Model;

class Duplication {
	/**
	 * @var string
	 */
	private $block;

	/**
	 * @var \Metrics\Model\LocationArray
	 */
	private $locations;

	/**
	 * Constructs a new duplication.
	 *
	 * @param string $block
	 * @param LocationArray $locations
	 */
	public function __construct( $block, LocationArray $locations ) {
		$this->block = (string) $block;
		$this->locations = $locations;
	}

	/**
	 * The duplicated block.
	 *
	 * @return string
	 */
	public function getBlock() {
		return $this->block;
	}

	/**
	 * The duplication locations.
	 *
	 * @return \Metrics\Model\LocationArray
	 */
	public function getLocations() {
		return $this->locations;
	}
}