<?php
namespace Mend\Metrics\Model;

class ComplexityModel {
	const LEVEL_LOW = 1;
	const LEVEL_MODERATE = 2;
	const LEVEL_HIGH = 3;
	const LEVEL_VERY_HIGH = 4;

	/**
	 * @var integer
	 */
	private $complexity;

	/**
	 * @var integer
	 */
	private $level;

	/**
	 * Constructs a new Complexity model.
	 *
	 * @param integer $complexity
	 * @param integer $level
	 */
	public function __construct( $complexity, $level ) {
		$this->complexity = $complexity;
		$this->level = $level;
	}

	/**
	 * Retrieves the complexity number.
	 *
	 * @return integer
	 */
	public function getComplexity() {
		return $this->complexity;
	}

	/**
	 * Retrieves the complexity risk level.
	 *
	 * @return integer
	 */
	public function getLevel() {
		return $this->level;
	}
}