<?php
namespace Mend\Mvc\Controller;

use Mend\IO\FileSystem\File;
use Mend\IO\FileSystem\FileSystem;
use Mend\Mvc\Context;
use Mend\Mvc\Controller;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View\ViewRenderer;
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
	 * @var ViewRenderer
	 */
	private $renderer;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * Constructs a new PageController instance.
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
		$this->init();
	}

	/**
	 * Initializer template method.
	 */
	protected function init() { /* no-op */ }

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
		if ( $this->getViewRenderer()->isDisabled() ) {
			return;
		}

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
		$templateFile = new File(
			$this->getControllerName()
			. FileSystem::DIRECTORY_SEPARATOR
			. $this->getActionName()
			. $this->context->getTemplateFileSuffix()
		);

		return $this->renderer->render( $templateFile );
	}

	/**
	 * Retrieves the context.
	 *
	 * @return Context
	 */
	protected function getContext() {
		return $this->context;
	}

	/**
	 * Sets a new context.
	 *
	 * @param Context $context
	 */
	protected function setContext( Context $context ) {
		$this->context = $context;
	}

	/**
	 * Retrieves the view renderer.
	 *
	 * @return ViewRenderer
	 */
	protected function getViewRenderer() {
		return $this->renderer;
	}

	/**
	 * Retrieves the current layout.
	 *
	 * @return Layout
	 */
	protected function getLayout() {
		return $this->renderer->getLayout();
	}

	/**
	 * Retrieves the current view.
	 *
	 * @return View
	 */
	protected function getView() {
		return $this->renderer->getView();
	}

	/**
	 * @see Controller::getControllerName()
	 */
	protected function getControllerName() {
		if ( is_null( $this->controllerName ) ) {
			$fullClassName = get_class( $this );
			$factory = $this->getFactory();
			$this->controllerName = $factory->getControllerNameByClass( $fullClassName );
		}

		return $this->controllerName;
	}

	/**
	 * @see Controller::getActionName()
	 */
	protected function getActionName() {
		return $this->actionName;
	}
}
