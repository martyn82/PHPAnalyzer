<?php
namespace Mend\Mvc\Controller;

use Mend\Mvc\View\Layout;
use Mend\Mvc\View\View;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\WebResponse;
use Mend\Network\Web\WebRequest;

abstract class Controller {
	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var WebResponse
	 */
	private $response;

	/**
	 * @var Layout
	 */
	private $layout;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var ViewRenderer
	 */
	private $renderer;

	/**
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

	/**
	 * Constructs a new controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ViewRenderer $renderer
	 * @param View $view
	 * @param Layout $layout
	 */
	public function __construct(
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		View $view = null,
		Layout $layout = null
	) {
		$this->request = $request;
		$this->response = $response;
		$this->renderer = $renderer;

		$this->view = $view ? : $this->createView();
		$this->layout = $layout;

		$this->init();
	}

	/**
	 * Initializes the controller.
	 */
	protected function init() { /* no-op */ }

	/**
	 * Called before dispatch.
	 */
	protected function preDispatch() { /* no-op */ }

	/**
	 * Called after dispatch.
	 */
	protected function postDispatch() {
		$rendered = $this->render( $this->actionName, $this->getControllerName() );
		$this->response->setBody( $rendered );
	}

	/**
	 * Dispatches the given action.
	 *
	 * @param string $actionName
	 *
	 * @throws ControllerException
	 */
	public function dispatchAction( $actionName ) {
		$action = ucfirst( $actionName );
		$actionMethod = "action{$action}";
		$controllerName = $this->getControllerName();

		if ( !method_exists( $this, $actionMethod ) ) {
			throw new ControllerException( "The action '{$action}' does not exist in controller '{$controllerName}'." );
		}

		$this->actionName = $actionName;

		$this->preDispatch();
		$this->{$actionMethod}();
		$this->postDispatch();
	}

	/**
	 * Retrieves the request.
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Retrieves the response.
	 *
	 * @return WebResponse
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Renders the current view.
	 *
	 * @param string $actionName
	 * @param string $controllerName
	 *
	 * @return string
	 */
	protected function render( $actionName, $controllerName ) {
		$content = $this->renderer->renderView( $this->view, $actionName, $controllerName );

		if ( is_null( $this->layout ) ) {
			return $content;
		}

		$this->layout->setContent( $content );
		return $this->renderer->renderLayout( $this->layout );
	}

	/**
	 * Creates a new View.
	 *
	 * @return View
	 */
	protected function createView() {
		return new View();
	}

	/**
	 * Sets a layout.
	 *
	 * @param Layout $layout
	 */
	protected function setLayout( Layout $layout ) {
		$this->layout = $layout;
	}

	/**
	 * Retrieves the current layout.
	 *
	 * @return Layout
	 */
	protected function getLayout() {
		return $this->layout;
	}

	/**
	 * Retrieves the current view.
	 *
	 * @return View
	 */
	protected function getView() {
		return $this->view;
	}

	/**
	 * Retrieves the view renderer.
	 *
	 * @return ViewRenderer
	 */
	protected function getRenderer() {
		return $this->renderer;
	}

	/**
	 * Retrieves the current controller name.
	 *
	 * @return string
	 */
	protected function getControllerName() {
		if ( is_null( $this->controllerName ) ) {
			$fullClassName = get_class( $this );

			$classParts = explode( '\\', $fullClassName );
			$className = end( $classParts );

			$controllerName = substr( $className, 0, strrpos( $className, 'Controller' ) );
			$this->controllerName = strtolower( $controllerName );
		}

		return $this->controllerName;
	}

	/**
	 * Retrieves the current action name.
	 *
	 * @return string
	 */
	protected function getActionName() {
		return $this->actionName;
	}
}
