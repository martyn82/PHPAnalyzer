<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Metrics\Model\Code\ClassModelArray;

class ClassPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;
		$classes = $this->getMock( '\Mend\Metrics\Model\Code\ClassModelArray' );

		$partition = new ClassPartition( $absolute, $relative, $classes );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $classes, $partition->getClasses() );
	}

	public function testEmpty() {
		$empty = ClassPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new ClassModelArray(), $empty->getClasses() );
	}
}