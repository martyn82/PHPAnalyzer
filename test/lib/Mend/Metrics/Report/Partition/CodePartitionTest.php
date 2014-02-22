<?php
namespace Mend\Metrics\Report\Partition;

class CodePartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;

		$partition = new CodePartition( $absolute, $relative );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );

		$expectedArray = array(
			'absolute' => $partition->getAbsolute(),
			'relative' => $partition->getRelative()
		);

		self::assertEquals( $expectedArray, $partition->toArray() );
	}

	public function testEmpty() {
		$empty = CodePartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
	}
}
