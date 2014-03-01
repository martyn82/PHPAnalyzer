<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Controller;
use Mend\Mvc\Layout;
use Mend\Mvc\View;
use Mend\Mvc\ViewRenderer;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\ControllerException;
use Mend\Mvc\Route;
use Mend\Collections\Map;

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

		$parameters = $request->getParameters();

		for ( $i = 0; $i < count( $parts ); $i += 2 ) {
			$key = $parts[ $i ];
			$value = isset( $parts[ $i + 1 ] ) ? $parts[ $i + 1 ] : null;

			$parameters->set( $key, $value );
		}

		$this->dispatch( $controllerName, $actionName );
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
	 *
	 * @throws ControllerException
	 */
	protected function createController( $controllerName ) {
		$controllerClassName = $this->loader->getControllerClassName( $controllerName );

		if ( !class_exists( $controllerClassName, true ) ) {
			throw new ControllerException( "Controller class not found: '{$controllerClassName}'." );
		}

		return new $controllerClassName(
			$this->getRequest(),
			$this->getResponse(),
			$this->getRenderer(),
			$this->getView(),
			$this->getLayout()
		);
	}
}
