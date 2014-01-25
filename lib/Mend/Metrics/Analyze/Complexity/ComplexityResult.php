<?php
namespace Mend\Metrics\Analyze\Complexity;

class ComplexityResult {
	/**
	 * @var integer
	 */
	private $complexity;

	/**
	 * @var integer
	 */
	private $level;

	/**
	 * Constructs a new complexity result.
	 *
	 * @param integer $complexity
	 * @param integer $level
	 */
	public function __construct( $complexity, $level ) {
		$this->complexity = (int) $complexity;
		$this->level = (int) $level;
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
	 * Retrieves the risk level.
	 *
	 * @return integer
	 */
	public function getLevel() {
		return $this->level;
	}
}
