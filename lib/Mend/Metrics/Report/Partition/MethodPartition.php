<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\MethodArray;

class MethodPartition extends CodePartition {
	/**
	 * @var MethodArray
	 */
	private $methods;

	/**
	 * Creates an empty partition.
	 *
	 * @return MethodPartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new MethodArray() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param MethodArray $methods
	 */
	public function __construct( $absolute, $relative, MethodArray $methods ) {
		parent::__construct( $absolute, $relative );
		$this->methods = $methods;
	}

	/**
	 * Retrieves the methods in this partition.
	 *
	 * @return MethodArray
	 */
	public function getMethods() {
		return $this->methods;
	}
}