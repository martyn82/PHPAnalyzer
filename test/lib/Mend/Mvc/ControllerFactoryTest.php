<?php
namespace Mend\Mvc;

use Mend\Mvc\Controller\PageController;

class ControllerFactoryTest extends \TestCase {
	/**
	 * @dataProvider mappingProvider
	 *
	 * @param array $mapping
	 * @param string $controllerName
	 * @param string $suffix
	 * @param string $expectedFullName
	 * @param boolean $classInMap
	 */
	public function testGetControllerClassName(
		array $mapping,
		$controllerName,
		$suffix,
		$expectedFullName,
		$classInMap
	) {
		$class = $this->createClassInformation( $classInMap );

		$factory = new ControllerFactory( $mapping, $suffix, $class );
		$className = $factory->getControllerClassByName( $controllerName );

		self::assertEquals( $expectedFullName, $className );
	}

	/**
	 * @dataProvider mappingProvider
	 *
	 * @param array $mapping
	 * @param string $controllerName
	 * @param string $suffix
	 * @param string $fullClassName
	 * @param boolean $classInMap
	 */
	public function testGetControllerNameByClass(
		array $mapping,
		$controllerName,
		$suffix,
		$fullClassName,
		$classInMap
	) {
		$class = $this->createClassInformation( $classInMap );

		$factory = new ControllerFactory( $mapping, $suffix, $class );
		$actual = $factory->getControllerNameByClass( $fullClassName );

		if ( !$classInMap ) {
			$controllerName = '';
		}

		self::assertEquals( $controllerName, $actual );
	}

	public function mappingProvider() {
		return array(
			array( array( 'Foo' ), 'foo', null, 'Foo\Foo', true ),
			array( array( 'Foo', 'Bar' ), 'bar', 'Controller', 'Foo\BarController', true ),
			array( array( 'Baz' ), 'baz', null, null, false )
		);
	}

	public function testCreateController() {
		$class = $this->createClassInformation( true );

		$request = $this->createRequest();
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$factory = new ControllerFactory( array( __NAMESPACE__ ), 'Controller', $class );
		$controller = $factory->createController( 'dummy', $request, $response, $renderer, $context );

		self::assertInstanceOf( '\Mend\Mvc\Controller\PageController', $controller );
	}

	/**
	 * @expectedException \Exception
	 */
	public function testCreateControllerFails() {
		$class = $this->createClassInformation( false );

		$request = $this->createRequest();
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$factory = new ControllerFactory( array( __NAMESPACE__ ), null, $class );
		$controller = $factory->createController( 'dummy', $request, $response, $renderer, $context );

		self::fail( "Test should have triggered an exception." );
	}

	/**
	 * @expectedException \Exception
	 */
	public function testCreateNonPageController() {
		$class = $this->createClassInformation( true, false );

		$request = $this->createRequest();
		$response = $this->createResponse();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$factory = new ControllerFactory( array( __NAMESPACE__ ), null, $class );
		$controller = $factory->createController( 'dummy', $request, $response, $renderer, $context );

		self::fail( "Test should have triggered an exception." );
	}

	private function createClassInformation( $exists, $isSubclassOf = true ) {
		$class = $this->getMock( '\Mend\ClassInformation', array( 'exists', 'isSubclassOf' ) );

		$class->expects( self::any() )
			->method( 'exists' )
			->will( self::returnValue( $exists ) );

		$class->expects( self::any() )
			->method( 'isSubclassOf' )
			->will( self::returnValue( $isSubclassOf) );

		return $class;
	}

	private function createContext() {
		return $this->getMockBuilder( '\Mend\Mvc\Context' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createViewRenderer() {
		return $this->getMockBuilder( '\Mend\Mvc\View\ViewRenderer' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createRequest() {
		return $this->getMockBuilder( '\Mend\Network\Web\WebRequest' )
			->disableOriginalConstructor()
			->getMock();
	}

	private function createResponse() {
		return $this->getMockBuilder( '\Mend\Network\Web\WebResponse' )
			->disableOriginalConstructor()
			->getMock();
	}
}

class DummyController extends PageController {
	protected function getControllerName() {}
	protected function getActionName() {}
}
