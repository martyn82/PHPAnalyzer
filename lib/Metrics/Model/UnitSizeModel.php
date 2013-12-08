<?php
namespace Metrics\Model;

class UnitSizeModel {
	const LEVEL_SMALL = 1;
	const LEVEL_MEDIUM = 2;
	const LEVEL_LARGE = 3;
	const LEVEL_VERY_LARGE = 4;

	/**
	 * @var integer
	 */
	private $size;

	/**
	 * @var integer
	 */
	private $level;

	/**
	 * Constructs a new UnitSize model.
	 *
	 * @param integer $size
	 * @param integer $level
	 */
	public function __construct( $size, $level ) {
		$this->size = $size;
		$this->level = $level;
	}

	/**
	 * Retrieves the size.
	 *
	 * @return integer
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * Retrieves the size level.
	 *
	 * @return integer
	 */
	public function getLevel() {
		return $this->level;
	}
}