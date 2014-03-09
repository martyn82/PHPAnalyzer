<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\Context;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View;
use Mend\Mvc\View\Layout;
use Mend\Mvc\View\ViewOptions;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

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
	 * @var ViewRenderer
	 */
	private $renderer;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * Constructs a new FrontController instance.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ControllerFactory $factory
	 * @param ViewRenderer $renderer
	 * @param Context $context
	 */
	public function __construct(
		WebRequest $request,
		WebResponse $response,
		ControllerFactory $factory,
		ViewRenderer $renderer,
		Context $context
	) {
		parent::__construct( $request, $response, $factory );
		$this->renderer = $renderer;
		$this->context = $context;
	}

	/**
	 * Dispatches current request.
	 */
	public function dispatchRequest() {
		$this->dispatch( $this->getControllerName(), $this->getActionName() );
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
	 * @return PageController
	 */
	protected function createController( $controllerName ) {
		$factory = $this->getFactory();

		return $factory->createController(
			$controllerName,
			$this->getRequest(),
			$this->getResponse(),
			$this->renderer,
			$this->context
		);
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
}
