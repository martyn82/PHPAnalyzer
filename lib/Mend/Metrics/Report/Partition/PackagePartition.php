<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\Package;
use Mend\Source\Code\Model\PackageHashTable;

class PackagePartition extends CodePartition {
	/**
	 * @var PackageHashTable
	 */
	private $packages;

	/**
	 * Creates an empty partition.
	 *
	 * @return PackagePartition
	 */
	public static function createEmpty() {
		return new self( 0, 0, new PackageHashTable() );
	}

	/**
	 * Constructs a new partition.
	 *
	 * @param integer $absolute
	 * @param float $relative
	 * @param PackageHashTable $packages
	 */
	public function __construct( $absolute, $relative, PackageHashTable $packages ) {
		parent::__construct( $absolute, $relative );
		$this->packages = $packages;
	}

	/**
	 * Retrieves the packages in this partition.
	 *
	 * @return PackageHashTable
	 */
	public function getPackages() {
		return $this->packages;
	}

	/**
	 * @see CodePartition::toArray()
	 */
	public function toArray() {
		$result = parent::toArray();
		$packages = array();

		foreach ( $this->packages as $name => $bucket ) {
			/* @var $package Package */
			$packages[] = $name;
		}

		$result[ 'packages' ] = $packages;
		return $result;
	}
}