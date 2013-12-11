<?php
namespace MVC;

abstract class Controller {
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var Layout
	 */
	private $layout;

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
	 * Constructs a new controller.
	 *
	 * @param string $viewScriptPath
	 * @param string $layoutScript
	 */
	public function __construct( $viewScriptPath, $layoutScript ) {
		$this->viewScriptPath = $viewScriptPath;
		$this->layoutScript = $layoutScript;

		$this->layout = new Layout();
		$this->view = new View();
	}

	/**
	 * Dispatches the given action.
	 *
	 * @param string $action
	 */
	public function dispatch( $action ) {
		if ( !method_exists( $this, 'action' . ucfirst( $action ) ) ) {
			$controller = $this->getController();
			throw new \Exception( "The action <{$action}> does not exist in controller <{$controller}>." );
		}

		$this->currentAction = $action;

		$this->preDispatch();

		$method = 'action' . ucfirst( $action );
		$this->{$method}();

		$this->postDispatch();
	}

	/**
	 * Template method called before dispatch.
	 */
	protected function preDispatch() {
		/* noop */
	}

	/**
	 * Template method called after dispatch.
	 */
	protected function postDispatch() {
		$renderedView = $this->render( $this->currentAction, $this->getController() );
		$this->sendResponse( $renderedView );
	}

	/**
	 * Renders the view.
	 *
	 * @param string $viewScript
	 * @param string $basePath
	 *
	 * @return string
	 */
	protected function render( $viewScript, $basePath = null ) {
		$basePath = $basePath ? DIRECTORY_SEPARATOR . $basePath : '';
		$viewScriptPath = $this->viewScriptPath . $basePath . DIRECTORY_SEPARATOR . $viewScript . ".phtml";

		$content = $this->view->render( $viewScriptPath );

		if ( is_null( $this->layout ) ) {
			return $content;
		}

		$this->layout->setContent( $content );
		return $this->layout->render( $this->layoutScript );
	}

	/**
	 * Sends response.
	 *
	 * @param string $body
	 * @param array $headers [optional]
	 */
	protected function sendResponse( $body, array $headers = null ) {
		$defaultHeaders = array(
			'Content-Type: text/html;charset=utf-8'
		);
		$headers = array_merge( $defaultHeaders, (array) $headers );

		foreach ( $headers as $header ) {
			header( $header );
		}

		print $body;
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
	 * Retrieves the layout view.
	 *
	 * @return Layout
	 */
	protected function getLayout() {
		return $this->layout;
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
	 * Retrieves the current action name.
	 *
	 * @return string
	 */
	private function getAction() {
		return $this->currentAction;
	}
}