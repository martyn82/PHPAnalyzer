<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Controller;
use Mend\Mvc\View\Layout;
use Mend\Mvc\View;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\View\ViewRenderer;
use Mend\Mvc\View\ViewOptions;

class FrontController extends Controller {
	/**
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

	/**
	 * Dispatches current request.
	 */
	public function dispatchRequest() {
		$controllerName = $this->getControllerName();
		$actionName = $this->getActionName();

		$this->dispatch( $controllerName, $actionName );
	}

	/**
	 * Dispatches to given controller and action name.
	 *
	 * @param string $controllerName
	 * @param string $actionName
	 */
	public function dispatch( $controllerName, $actionName ) {
		$controller = $this->createController( $controllerName );
		$controller->dispatchAction( $actionName );
	}

	/**
	 * Creates a new controller instance by name.
	 *
	 * @param string $controllerName
	 *
	 * @return Controller
	 */
	protected function createController( $controllerName ) {
		$viewOptions = new ViewOptions();
		$viewOptions->setRendererEnabled( true );
		$viewOptions->setLayoutEnabled( true );
		$viewOptions->setLayoutTemplate( 'default.phtml' );
		$viewOptions->setLayoutTemplatePath( 'views/layout/' );
		$viewOptions->setViewTemplatePath( 'views/' );

		$renderer = new ViewRenderer( $viewOptions, new View(), new Layout() );
		$factory = $this->getFactory();

		return $factory->createController( $controllerName, $this->getRequest(), $this->getResponse(), $renderer );
	}

	/**
	 * @see Controller::getControllerName()
	 */
	protected function getControllerName() {
		if ( is_null( $this->controllerName ) ) {
			$this->parseRequest();
		}

		return $this->controllerName;
	}

	/**
	 * @see Controller::getActionName()
	 */
	protected function getActionName() {
		return $this->actionName;
	}

	/**
	 * Parses the current request.
	 *
	 * @param string $defaultController
	 * @param string $defaultAction
	 */
	private function parseRequest( $defaultController = 'index', $defaultAction = 'index' ) {
		$request = $this->getRequest();
		$requestUrl = $request->getUrl();
		$path = $requestUrl->getPath();

		$parts = explode( '/', trim( $path, '/' ) );

		$this->controllerName = array_shift( $parts ) ? : $defaultController;
		$this->actionName = array_shift( $parts ) ? : $defaultAction;

		$parameters = $request->getParameters();

		for ( $i = 0; $i < count( $parts ); $i += 2 ) {
			$key = $parts[ $i ];
			$value = isset( $parts[ $i + 1 ] ) ? $parts[ $i + 1 ] : null;

			$parameters->set( $key, $value );
		}
	}
}
