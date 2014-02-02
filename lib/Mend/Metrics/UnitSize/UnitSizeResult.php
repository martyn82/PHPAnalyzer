<?php
namespace Mend\Metrics\UnitSize;

class UnitSizeResult {
	/**
	 * @var integer
	 */
	private $unitSize;

	/**
	 * @var integer
	 */
	private $category;

	/**
	 * Constructs a new unit size result.
	 *
	 * @param integer $unitSize
	 * @param integer $category
	 */
	public function __construct( $unitSize, $category ) {
		$this->unitSize = (int) $unitSize;
		$this->category = (int) $category;
	}

	/**
	 * Retrieves the unit size.
	 *
	 * @return integer
	 */
	public function getUnitSize() {
		return $this->unitSize;
	}

	/**
	 * Retrieves the unit size category.
	 *
	 * @return integer
	 */
	public function getCategory() {
		return $this->category;
	}
}