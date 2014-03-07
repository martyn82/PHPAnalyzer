<?php
namespace Mend\Mvc\Controller;

use Mend\Collections\Map;
use Mend\Mvc\View\Layout;
use Mend\Mvc\View\View;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class FrontController extends Controller {
	/**
	 * @var ControllerLoader
	 */
	private $loader;

	/**
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

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
		$controllerName = $this->getControllerName();
		$actionName = $this->getActionName();

		$this->dispatch( $controllerName, $actionName );
	}

	/**
	 * Parses the current request.
	 *
	 * @param string $defaultController
	 * @param string $defaultAction
	 */
	protected function parseRequest( $defaultController = 'index', $defaultAction = 'index' ) {
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
	 * Dispatch to given controller and action name.
	 *
	 * @param string $controllerName
	 * @param string $actionName
	 */
	public function dispatch( $controllerName, $actionName ) {
		$this->preDispatch();

		$controller = $this->createController( $controllerName );
		$controller->dispatchAction( $actionName );

		$this->setActionResult( $controller->getActionResult() );

		$this->postDispatch();
	}

	/**
	 * @see Controller::postDispatch()
	 */
	protected function postDispatch() { /* no-op */ }

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
