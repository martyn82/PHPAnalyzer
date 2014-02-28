<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Controller;
use Mend\Mvc\Layout;
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
	 * @see Controller::setLayout()
	 */
	public function setLayout( Layout $layout ) {
		parent::setLayout( $layout );
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
	 * Sends the response.
	 *
	 * @param WebResponse $response
	 */
	public function sendResponse( WebResponse $response = null ) {
		$response = $response ? : $this->getResponse();

		// @todo we should implement some kind of Transport class to facilitate the header setting and print statement.

		$headers = $response->getHeaders();

		foreach ( (array) $headers as $name => $value ) {
			header( $name . ': ' . $value );
		}

		header( 'HTTP/1.1 ' . (string) $response->getStatusCode() . '  ' . $response->getStatusDescription() );
		print $response->getBody();
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

		return new $controllerClassName(
			$this->getRequest(),
			$this->getResponse(),
			$this->getRenderer(),
			$this->getView(),
			$this->getLayout()
		);
	}
}
