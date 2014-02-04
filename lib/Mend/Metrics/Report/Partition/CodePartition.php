<?php
namespace Mend\Metrics\Report\Partition;

class CodePartition {
	/**
	 * @var integer
	 */
	private $absolute;

	/**
	 * @var float
	 */
	private $relative;

	/**
	 * Creates an empty code partition instance.
	 *
	 * @return CodePartition
	 */
	public static function createEmpty() {
		return new self( 0, 0 );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 */
	public function __construct( $absolute, $relative ) {
		$this->absolute = (int) $absolute;
		$this->relative = (float) $relative;
	}

	/**
	 * Retrieves the absolute amount.
	 *
	 * @return integer
	 */
	public function getAbsolute() {
		return $this->absolute;
	}

	/**
	 * Retrieves the relative amount.
	 *
	 * @return float
	 */
	public function getRelative() {
		return $this->relative;
	}

	/**
	 * Converts this object to its array representation.
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'absolute' => $this->absolute,
			'relative' => $this->relative
		);
	}
}