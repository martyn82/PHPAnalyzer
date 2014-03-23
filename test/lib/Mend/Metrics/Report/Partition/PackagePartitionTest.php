<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\PackageHashTable;
use Mend\Source\Code\Model\Package;

class PackagePartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;

		$packages = new PackageHashTable();
		$packages[] = $this->getMockBuilder( '\Mend\Source\Code\Model\Package' )
			->disableOriginalConstructor()
			->getMock();

		$partition = new PackagePartition( $absolute, $relative, $packages );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $packages, $partition->getPackages() );

		$expectedArray = array(
			'absolute' => $absolute,
			'relative' => $relative,
			'packages' => array_map(
				function ( $packageName ) {
					return $packageName;
				},
				array_keys( (array) $packages )
			)
		);

		self::assertEquals( $expectedArray, $partition->toArray() );
	}

	public function testEmpty() {
		$empty = PackagePartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new PackageHashTable(), $empty->getPackages() );
	}
}