<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Model\Code\ClassModelArray;

class ClassPartition extends CodePartition {
	/**
	 * @var ClassModelArray
	 */
	private $classes;

	/**
	 * Creates an empty partition.
	 *
	 * @return ClassPartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new ClassModelArray() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param ClassModelArray $classes
	 */
	public function __construct( $absolute, $relative, ClassModelArray $classes ) {
		parent::__construct( $absolute, $relative );
		$this->classes = $classes;
	}

	/**
	 * Retrieves the classes in this partition.
	 *
	 * @return ClassModelArray
	 */
	public function getClasses() {
		return $this->classes;
	}
}