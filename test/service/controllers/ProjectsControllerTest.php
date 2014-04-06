<?php
namespace Controller;

require_once "ControllerTest.php";

use Mend\Data\DataObjectCollection;
use Mend\IO\FileSystem\Directory;
use Mend\Network\Web\Url;
use Model\Project\Project;

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
			->setMethods( array( 'loadData', 'get', 'all' ) )
			->getMock();

		$repository->expects( self::any() )
			->method( 'loadData' )
			->will( self::returnValue( array() ) );

		$project = new Project( 'name', 'key', new Directory( '/foo' ) );
		$project->reports = array();

		$repository->expects( self::any() )
			->method( 'get' )
			->will( self::returnValue( $project ) );

		$collection = new DataObjectCollection();
		$collection->add( $project );

		$repository->expects( self::any() )
			->method( 'all' )
			->will( self::returnValue( $collection ) );

		$this->controller = new ProjectsController( $request, $response, $factory, $renderer, $context );
		$this->controller->setRepository( $repository );
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
