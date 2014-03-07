<?php
namespace Mend\Mvc\Controller;

use Mend\IO\FileSystem\File;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View;
use Mend\Mvc\View\Layout;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

abstract class PageController extends Controller {
	/**
	 * @var string
	 */
	private $controllerName;

	/**
	 * @var string
	 */
	private $actionName;

	/**
	 * @var Layout
	 */
	private $layout;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var boolean
	 */
	private $layoutEnabled;

	/**
	 * @var boolean
	 */
	private $renderEnabled;

	/**
	 * @var string
	 */
	private $viewTemplatePath;

	/**
	 * @var string
	 */
	private $layoutTemplatePath;

	/**
	 * @var string
	 */
	private $layoutTemplate;

	/**
	 * Dispatches given action.
	 *
	 * @param string $actionName
	 *
	 * @throws ControllerException
	 */
	public function dispatchAction( $actionName ) {
		$controllerName = $this->getControllerName();

		$action = ucfirst( $actionName );
		$actionMethod = "action{$action}";

		if ( !method_exists( $this, $actionMethod ) ) {
			throw new ControllerException(
				"The action '{$actionName}' does not exist in controller '{$controllerName}'."
			);
		}

		$this->actionName = $actionName;

		$this->preDispatch();
		$this->{$actionMethod}();
		$this->postDispatch();
	}

	/**
	 * Called before dispatch action.
	 */
	protected function preDispatch() { /* no-op */ }

	/**
	 * Called after dispatch action.
	 */
	protected function postDispatch() {
		$rendered = $this->render();

		$response = $this->getResponse();
		$response->setBody( $rendered );
	}

	/**
	 * Renders the current view.
	 *
	 * @return string
	 */
	protected function render() {
		if ( !$this->renderEnabled || is_null( $this->view ) ) {
			return '';
		}

		$templateFile = new File( $this->viewTemplatePath . DIRECTORY_SEPARATOR . $this->getActionName() . '.phtml' );
		$renderedView = $this->view->render( $templateFile );

		if ( $this->layoutEnabled && !is_null( $this->layout ) ) {
			$this->layout->setContent( $renderedView );

			$layoutFile = new File( $this->layoutTemplatePath . DIRECTORY_SEPARATOR . $this->layoutTemplate );
			$renderedView = $this->layout->render( $layoutFile );
		}

		return $renderedView;
	}

	/**
	 * @see Controller::getControllerName()
	 */
	protected function getControllerName() {
		if ( is_null( $this->controllerName ) ) {
			$fullClassName = get_class( $this );
			$this->controllerName = $this->getFactory()->getControllerNameByClass( $fullClassName );
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
	 * Sets the Layout.
	 *
	 * @param Layout $layout
	 */
	public function setLayout( Layout $layout ) {
		$this->layout = $layout;
	}

	/**
	 * Retrieves the layout.
	 *
	 * @return Layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * Sets the view.
	 *
	 * @param View $view
	 */
	public function setView( View $view ) {
		$this->view = $view;
	}

	/**
	 * Retrieves the view.
	 *
	 * @return View
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Enables or disables the layout.
	 *
	 * @param boolean $enable
	 */
	public function enableLayout( $enable = true ) {
		$this->layoutEnabled = (bool) $enable;
	}

	/**
	 * Enables or disables the view rendering.
	 *
	 * @param boolean $enable
	 */
	public function enableRender( $enable = true ) {
		$this->renderEnabled = (bool) $enable;
	}

	/**
	 * Sets the path to view templates.
	 *
	 * @param string $path
	 */
	public function setViewTemplatePath( $path ) {
		$this->viewTemplatePath = (string) $path;
	}

	/**
	 * Sets the path to layout templates.
	 *
	 * @param string $path
	 */
	public function setLayoutTemplatePath( $path ) {
		$this->layoutTemplatePath = (string) $path;
	}

	/**
	 * Sets the layout template file name.
	 *
	 * @param string $template
	 */
	public function setLayoutTemplate( $template ) {
		$this->layoutTemplate = (string) $template;
	}
}
