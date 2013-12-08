<?php
namespace MVC;

abstract class Controller {
	/**
	 * @var \MVC\View
	 */
	protected $view;

	/**
	 * @var string
	 */
	private $viewScriptPath;

	/**
	 * @var string
	 */
	private $currentAction;

	/**
	 * Constructs a new controller.
	 *
	 * @param string $viewScriptPath
	 */
	public function __construct( $viewScriptPath ) {
		$this->viewScriptPath = $viewScriptPath;
		$this->view = new View();
	}

	/**
	 * Dispatches the given action.
	 *
	 * @param string $action
	 */
	public function dispatch( $action ) {
		if ( !method_exists( $this, 'action' . ucfirst( $action ) ) ) {
			$controller = get_class( $this );
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
	protected function preDispatch() {}

	/**
	 * Template method called after dispatch.
	 */
	protected function postDispatch() {
		$renderedView = $this->render( $this->currentAction );
		$this->sendResponse( $renderedView );
	}

	/**
	 * Renders the view and sends response.
	 *
	 * @param string $viewScript
	 */
	protected function render( $viewScript ) {
		$viewScriptPath = $this->viewScriptPath . DIRECTORY_SEPARATOR . $viewScript . ".phtml";
		return $this->view->render( $viewScriptPath );
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
}