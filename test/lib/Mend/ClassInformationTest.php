<?php
namespace Mend;

class ClassInformationTest extends \TestCase {
	/**
	 * @dataProvider classProvider
	 *
	 * @param string $className
	 * @param boolea $exists
	 */
	public function testClassExists( $className, $exists ) {
		$classInfo = new ClassInformation();
		$actual = $classInfo->exists( $className, false );

		self::assertEquals( $exists, $actual );
	}

	/**
	 * @dataProvider classProvider
	 *
	 * @param string $className
	 * @param boolean $exists
	 * @param string $parentClassName
	 * @param boolean $subclass
	 */
	public function testIsSubclassOf( $className, $exists, $parentClassName, $subclass ) {
		$classInfo = new ClassInformation();
		$actual = $classInfo->isSubclassOf( $className, $parentClassName );

		self::assertEquals( $subclass, $actual );
	}

	public function classProvider() {
		return array(
			array( '\Mend\DummyClass', true, null, false ),
			array( '\Mend\_DummyClass', false, null, false ),
			array( '\Mend\DummySubClass', true, '\Mend\DummyClass', true ),
			array( '\Mend\_DummySubClass', false, '\Mend\DummyClass', false )
		);
	}
}

class DummyClass {}
class DummySubClass extends DummyClass {}
