<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\MethodArray;

class MethodPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;
		$methods = $this->getMock( '\Mend\Source\Code\Model\MethodArray' );

		$partition = new MethodPartition( $absolute, $relative, $methods );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $methods, $partition->getMethods() );
	}

	public function testEmpty() {
		$empty = MethodPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new MethodArray(), $empty->getMethods() );
	}
}