<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Controller;
use Mend\Mvc\View;
use Mend\Mvc\ViewRenderer;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class FrontController extends Controller {
	/**
	 * @var ControllerLoader
	 */
	private $loader;

	/**
	 * Constructs a new Front Controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ViewRenderer $renderer
	 * @param ControllerLoader $loader
	 */
	public function __construct(
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		ControllerLoader $loader
	) {
		parent::__construct( $request, $response, $renderer );
		$this->loader = $loader;
	}

	/**
	 * Dispatches a request.
	 */
	public function dispatchRequest() {
		$request = $this->getRequest();
		$requestUrl = $request->getUrl();
		$path = $requestUrl->getPath();

		$parts = explode( '/', trim( $path, '/' ) );

		$controllerName = array_shift( $parts ) ? : 'index';
		$actionName = array_shift( $parts ) ? : 'index';

		$controller = $this->createController( $controllerName );
		$controller->dispatchAction( $actionName );
	}

	/**
	 * Dispatch to given controller and action name.
	 *
	 * @param string $controllerName
	 * @param string $actionName
	 */
	public function dispatch( $controllerName, $actionName ) {
		$controller = $this->createController( $controllerName );
		$controller->dispatchAction( $actionName );
	}

	/**
	 * Creates a controller instance by name.
	 *
	 * @param string $controllerName
	 *
	 * @return Controller
	 */
	private function createController( $controllerName ) {
		$controllerClassName = $this->loader->getControllerClassName( $controllerName );
		return new $controllerClassName( $this->getRequest(), $this->getResponse(), $this->getRenderer() );
	}
}
