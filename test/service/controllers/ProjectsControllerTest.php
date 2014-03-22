<?php
namespace Controller;

require_once "ControllerTest.php";

use Mend\IO\FileSystem\Directory;
use Mend\Network\Web\Url;
use Mend\Metrics\Project\Project;

class ProjectsControllerTest extends ControllerTest {
	private $controller;

	public function setUp() {
		$url = $this->createUrl( 'http://www.example.org/projects' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );

		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$repository = $this->getMockBuilder( '\Model\Project\ProjectRepository' )
			->disableOriginalConstructor()
			->setMethods( array( 'loadData', 'get' ) )
			->getMock();

		$repository->expects( self::any() )
			->method( 'loadData' )
			->will( self::returnValue( array() ) );

		$record = new Project( 'name', 'key', new Directory( '/foo' ) );
		$record->reports = array();

		$repository->expects( self::any() )
			->method( 'get' )
			->will( self::returnValue( $record ) );

		$this->controller = new ProjectsController( $request, $response, $factory, $renderer, $context, $repository );
	}

	public function testActionIndex() {
		$this->controller->dispatchAction( 'index' );
	}

	public function testActionRead() {
		$this->controller->dispatchAction( 'read' );
	}

	public function testActionCreate() {
		$this->controller->dispatchAction( 'create' );
	}

	public function testActionUpdate() {
		$this->controller->dispatchAction( 'update' );
	}

	public function testActionDelete() {
		$this->controller->dispatchAction( 'delete' );
	}
}
