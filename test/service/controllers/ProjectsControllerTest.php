<?php
namespace Controller;

require_once "ControllerTest.php";

use Mend\Network\Web\Url;

class ProjectsControllerTest extends ControllerTest {
	private $controller;

	public function setUp() {
		$url = $this->createUrl( 'http://www.example.org/projects' );
		$request = $this->createRequest( $url );
		$response = $this->createResponse( $url );

		$factory = $this->createFactory();
		$renderer = $this->createViewRenderer();
		$context = $this->createContext();

		$this->controller = new ProjectsController( $request, $response, $factory, $renderer, $context );
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
