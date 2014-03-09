<?php
namespace Mend;

use Mend\Mvc\View\ViewRenderer;
use Mend\Mvc\View\ViewOptions;
use Mend\Mvc\View;
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
			array( '\Mend\_DummySubClass', false, '\Mend\DummyClass', false ),
			array( '\Mend\DummyClass', true, '\Mend\DummyClass', true )
		);
	}

	/**
	 * @dataProvider objectProvider
	 *
	 * @param object $object
	 * @param string $expected
	 */
	public function testGetClass( $object, $expected ) {
		$classInfo = new ClassInformation();
		$actual = $classInfo->getClassName( $object );

		self::assertEquals( $expected, $actual );
	}

	public function objectProvider() {
		return array(
			array( new \stdClass(), '\stdClass' ),
			array( new DummyClass(), '\Mend\DummyClass' )
		);
	}

	/**
	 * @dataProvider nonObjectProvider
	 * @expectedException \InvalidArgumentException
	 *
	 * @param mixed $nonObject
	 */
	public function testGetClassNonObject( $nonObject ) {
		$classInfo = new ClassInformation();
		$classInfo->getClassName( $nonObject );

		self::fail( "Test should have triggered an exception." );
	}

	public function nonObjectProvider() {
		return array(
			array( 1 ),
			array( -4832 ),
			array( 'str' ),
			array( false ),
			array( true ),
			array( 0.278 ),
			array( 0x4af1 )
		);
	}
}

class DummyClass {}
class DummySubClass extends DummyClass {}
