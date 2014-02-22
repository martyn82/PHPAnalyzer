<?php
namespace Mend\Metrics\Report\Partition;

use Mend\Source\Code\Model\ClassModelArray;
use Mend\Source\Code\Model\ClassModel;

class ClassPartitionTest extends \TestCase {
	public function testAccessors() {
		$absolute = mt_rand( 0, PHP_INT_MAX );
		$relative = (float) mt_rand( 1, PHP_INT_MAX ) / PHP_INT_MAX;

		$classes = new ClassModelArray();
		$classes[] = $this->getMock( '\Mend\Source\Code\Model\ClassModel', array(), array(), '', false );

		$partition = new ClassPartition( $absolute, $relative, $classes );

		self::assertEquals( $absolute, $partition->getAbsolute() );
		self::assertEquals( $relative, $partition->getRelative() );
		self::assertEquals( $classes, $partition->getClasses() );

		$expectedArray = array(
			'absolute' => $absolute,
			'relative' => $relative,
			'classes' => array_map(
				function ( ClassModel $class ) {
					return $class->toArray();
				},
				(array) $classes
			)
		);

		self::assertEquals( $expectedArray, $partition->toArray() );
	}

	public function testEmpty() {
		$empty = ClassPartition::createEmpty();

		self::assertEquals( 0, $empty->getAbsolute() );
		self::assertEquals( 0, $empty->getRelative() );
		self::assertEquals( new ClassModelArray(), $empty->getClasses() );
	}
}