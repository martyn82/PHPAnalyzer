<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\PackageArray;

class PackagePartition extends CodePartition {
	/**
	 * @var PackageArray
	 */
	private $packages;

	/**
	 * Creates an empty partition.
	 *
	 * @return PackagePartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new PackageArray() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param PackageArray $packages
	 */
	public function __construct( $absolute, $relative, PackageArray $packages ) {
		parent::__construct( $absolute, $relative );
		$this->packages = $packages;
	}

	/**
	 * Retrieves the packages in this partition.
	 *
	 * @return PackageArray
	 */
	public function getPackages() {
		return $this->packages;
	}
}