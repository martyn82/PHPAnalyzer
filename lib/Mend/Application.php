<?php
namespace Mend;

use Mend\Config\ConfigProvider;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\Controller\FrontController;
use Mend\Mvc\View\Layout;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class Application {
	/**
	 * @var ConfigProvider
	 */
	private $config;

	/**
	 * @var FrontController
	 */
	private $controller;

	/**
	 * Constructs a new Application instance.
	 *
	 * @param ConfigProvider $config
	 */
	public function __construct( ConfigProvider $config ) {
		$this->config = $config;
		$this->init();
	}

	/**
	 * Initializes the application.
	 */
	protected function init() {
		$request = $this->createRequest();
		$response = $this->createResponse( $request->getUrl() );
		$factory = $this->createControllerFactory();

		$this->controller = $this->createController( $request, $response, $factory );
	}

	/**
	 * Initializes the request.
	 *
	 * @return WebRequest
	 */
	protected function createRequest() {
		return WebRequest::createFromGlobals();
	}

	/**
	 * Initializes the response.
	 *
	 * @param Url $url
	 *
	 * @return WebResponse
	 */
	protected function createResponse( Url $url ) {
		return new WebResponse( $url );
	}

	/**
	 * Initializes the controller factory.
	 *
	 * @return ControllerFactory
	 */
	protected function createControllerFactory() {
		$controllerClassSuffix = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_SUFFIX );
		$controllerNamespaces = $this->config->getArray( ApplicationConfigKey::CONTROLLER_CLASS_NAMESPACES );

		return new ControllerFactory( $controllerNamespaces, $controllerClassSuffix );
	}

	/**
	 * Initializes the main controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ControllerFactory $factory
	 *
	 * @return FrontController
	 */
	protected function createController( WebRequest $request, WebResponse $response, ControllerFactory $factory ) {
		$controllerClassName = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_MAIN );
		$controller = new $controllerClassName( $request, $response, $factory );

		return $controller;
	}

	/**
	 * Retrieves the main controller.
	 *
	 * @return FrontController
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * Runs the application.
	 */
	public function run() {
		$this->controller->dispatchRequest();
	}
}