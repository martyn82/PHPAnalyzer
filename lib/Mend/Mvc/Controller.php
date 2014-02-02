<?php
namespace Mend\Mvc;

use Mend\Network\Web\WebResponse;
use Mend\Network\Web\Url;
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
	private $viewScriptPath;

	/**
	 * @var string
	 */
	private $layoutScript;

	/**
	 * @var string
	 */
	private $currentAction;

	/**
	 * @var string
	 */
	private $currentController;

	/**
	 * Constructs a new Controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 */
	public function __construct( WebRequest $request, WebResponse $response ) {
		$this->request = $request;
		$this->response = $response;

		$this->layout = new Layout();
		$this->view = new View();
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
	 * Dispatch given action to current controller.
	 *
	 * @param string $action
	 *
	 * @throws ControllerException
	 */
	public function dispatch( $action ) {
		$methodName = 'action' . ucfirst( $action );

		if ( !method_exists( $this, $methodName ) ) {
			$controller = $this->getController();
			throw new ControllerException( "The action <{$action}> does not exist in controller <{$controller}>." );
		}

		$this->currentAction = $action;

		$this->preDispatch();
		$this->{$methodName}();
		$this->postDispatch();
	}

	/**
	 * Called before action dispatch.
	 */
	protected function preDispatch() {
		/* noop */
	}

	/**
	 * Called after action dispatch.
	 */
	protected function postDispatch() {
		$renderedView = $this->render( $this->currentAction, $this->getController() );

		$url = Url::createFromGlobals();
		$response = new WebResponse( $url, null, $renderedView );

		$this->sendResponse( $response );
	}

	/**
	 * Renders the view onto given view script file.
	 *
	 * @param string $viewScriptFile
	 * @param string $basePath
	 *
	 * @return string
	 */
	protected function render( $viewScriptFile, $basePath = null, $scriptSuffix = ".phtml" ) {
		$basePath = $basePath
			? DIRECTORY_SEPARATOR . $basePath
			: '';

		$viewScriptPath = $this->viewScriptPath
			. $basePath
			. DIRECTORY_SEPARATOR
			. $viewScriptFile
			. $scriptSuffix;

		$content = $this->view->render( $viewScriptPath );

		if ( is_null( $this->layout ) ) {
			return $content;
		}

		$this->layout->setContent( $content );
		return $this->layout->render( $this->layoutScript );
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
	 * Retrieves the current controller name.
	 *
	 * @return string
	 */
	private function getController() {
		if ( is_null( $this->currentController ) ) {
			$fullClassName = get_class( $this );

			$classParts = explode( "\\", $fullClassName );
			$className = end( $classParts );

			$controllerName = substr( $className, 0, strrpos( $className, 'Controller' ) );
			$this->currentController = strtolower( $controllerName );
		}

		return $this->currentController;
	}

	/**
	 * Retrieves the current action.
	 *
	 * @return string
	 */
	private function getAction() {
		return $this->currentAction;
	}

	/**
	 * Retrieves the view.
	 *
	 * @return View
	 */
	protected function getView() {
		return $this->view;
	}

	/**
	 * Retrieves the layout.
	 *
	 * @return Layout
	 */
	protected function getLayout() {
		return $this->layout;
	}
}