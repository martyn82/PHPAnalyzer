<?php
namespace Controller;

use Mend\Network\Web\Url;

class ProjectsControllerTest extends ControllerTest {
	private $request;
	private $response;
	private $factory;
	private $renderer;

	public function setUp() {
		$url = $this->createUrl( 'http://www.example.org/projects' );
		$this->request = $this->createRequest( $url );
		$this->response = $this->createResponse( $url );

		$this->factory = $this->createFactory();
		$this->renderer = $this->createViewRenderer();
	}

	public function testActionIndex() {
		$controller = new ProjectsController( $this->request, $this->response, $this->factory, $this->renderer );
		$controller->dispatchAction( 'index' );
	}

	public function testActionRead() {
		$controller = new ProjectsController( $this->request, $this->response, $this->factory, $this->renderer );
		$controller->dispatchAction( 'read' );
	}

	public function testActionCreate() {
		$controller = new ProjectsController( $this->request, $this->response, $this->factory, $this->renderer );
		$controller->dispatchAction( 'create' );
	}

	public function testActionUpdate() {
		$controller = new ProjectsController( $this->request, $this->response, $this->factory, $this->renderer );
		$controller->dispatchAction( 'update' );
	}

	public function testActionDelete() {
		$controller = new ProjectsController( $this->request, $this->response, $this->factory, $this->renderer );
		$controller->dispatchAction( 'delete' );
	}
}
