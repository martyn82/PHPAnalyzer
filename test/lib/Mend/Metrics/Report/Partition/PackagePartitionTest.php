<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Model\Code\PackageArray;

class PackagePartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;
		$packages = $this->getMock( '\Mend\Metrics\Model\Code\PackageArray' );

		$partition = new PackagePartition( $absolute, $relative, $packages );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $packages, $partition->getPackages() );
	}

	public function testEmpty() {
		$empty = PackagePartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new PackageArray(), $empty->getPackages() );
	}
}