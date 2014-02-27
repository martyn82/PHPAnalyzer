<?php
namespace Mend\Mvc;

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
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

	/**
	 * @var string
	 */
	private $viewScriptPath;

	/**
	 * @var string
	 */
	private $layoutScriptPath;

	/**
	 * Constructs a new controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 */
	public function __construct( WebRequest $request, WebResponse $response ) {
		$this->request = $request;
		$this->response = $response;

		$this->layout = $this->createLayout();
		$this->view = $this->createView();

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

		$this->sendResponse( $response );
	}

	/**
	 * Dispatches the given action.
	 *
	 * @param string $actionName
	 *
	 * @throws ControllerException
	 */
	public function dispatch( $actionName ) {
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
	 * Sends the given response.
	 *
	 * @param WebResponse $response
	 */
	public function sendResponse( WebResponse $response ) {
		$headers = $response->getHeaders()->toArray();

		foreach ( $headers as $key => $value ) {
			header( $key . ': ' . $value );
		}

		header( 'HTTP/1.1 ' . (string) $response->getStatusCode() . ' ' . $response->getStatusDescription() );
		print $response->getBody();
	}

	/**
	 * Renders the given view script.
	 *
	 * @param string $viewScript
	 * @param string $basePath
	 * @param string $viewScriptSuffix
	 *
	 * @return string
	 */
	protected function render( $viewScript, $basePath = null, $viewScriptSuffix = '.phtml' ) {
		$basePath = $basePath
			? DIRECTORY_SEPARATOR . $basePath
			: '';

		$viewScriptPath = $this->viewScriptPath
			. $basePath
			. DIRECTORY_SEPARATOR
			. $viewScript
			. $viewScriptSuffix;

		$content = $this->view->render( $viewScriptPath );

		if ( is_null( $this->layout ) ) {
			return $content;
		}

		$this->layout->setContent( $content );
		return $this->layout->render( $this->layoutScript );
	}

	/**
	 * Sets the view script path.
	 *
	 * @param string $viewScriptPath
	 */
	public function setViewScriptPath( $viewScriptPath ) {
		$this->viewScriptPath = $viewScriptPath;
	}

	/**
	 * Sets the layout script file.
	 *
	 * @param string $layoutScript
	 */
	public function setLayoutScript( $layoutScript ) {
		$this->layoutScript = $layoutScript;
	}

	/**
	 * Creates a new Layout.
	 *
	 * @return Layout
	 */
	protected function createLayout() {
		return new Layout();
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
