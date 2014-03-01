<?php
namespace Mend\Mvc\Controller;

require_once 'ControllerClassExists.php';

class ControllerLoaderTest extends \TestCase {
	public function setUp() {
		ControllerClassExists::$classExistsResult = null;
	}

	public function tearDown() {
		ControllerClassExists::$classExistsResult = null;
	}

	/**
	 * @dataProvider mappingProvider
	 *
	 * @param array $mapping
	 * @param string $controllerName
	 * @param string $expectedFullName
	 * @param boolean $classInMap
	 */
	public function testGetControllerClassName( array $mapping, $controllerName, $expectedFulName, $classInMap ) {
		ControllerClassExists::$classExistsResult = $classInMap;

		if ( !$classInMap ) {
			self::setExpectedException( '\Mend\Mvc\ControllerException' );
		}

		$loader = new ControllerLoader( $mapping );
		$className = $loader->getControllerClassName( $controllerName );

		self::assertEquals( $expectedFulName, $className );
	}

	public function mappingProvider() {
		return array(
			array( array( 'Foo' ), 'foo', 'Foo\FooController', true ),
			array( array( 'Foo', 'Bar' ), 'bar', 'Foo\BarController', true ),
			array( array( 'Baz' ), 'baz', 'Baz\baz', false )
		);
	}
}
