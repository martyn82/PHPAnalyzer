<?php
namespace Application;

use Mend\Config\ConfigProvider;
use Mend\Mvc\Controller;
use Mend\Mvc\Controller\ControllerLoader;
use Mend\Mvc\Layout;
use Mend\Mvc\ViewRenderer;
use Mend\Mvc\ViewRendererOptions;
use Mend\Network\Web\Url;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

class Application {
	/**
	 * @var ConfigProvider
	 */
	private $config;

	/**
	 * @var Controller
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
		$request = $this->initRequest();
		$response = $this->initResponse( $request->getUrl() );
		$renderer = $this->initViewRenderer();
		$this->controller = $this->initController( $request, $response, $renderer );
	}

	/**
	 * Initializes the request.
	 *
	 * @return WebRequest
	 */
	private function initRequest() {
		return WebRequest::createFromGlobals();
	}

	/**
	 * Initializes the response.
	 *
	 * @param Url $url
	 *
	 * @return WebResponse
	 */
	private function initResponse( Url $url ) {
		return new WebResponse( $url );
	}

	/**
	 * Initializes the view renderer.
	 *
	 * @return ViewRenderer
	 */
	private function initViewRenderer() {
		$viewPath = realpath( $this->config->getString( ApplicationConfigKey::VIEW_PATH ) );
		$viewSuffix = $this->config->getString( ApplicationConfigKey::VIEW_TEMPLATE_SUFFIX );

		$layoutPath = realpath( $this->config->getString( ApplicationConfigKey::LAYOUT_PATH ) );
		$layoutTemplate = $this->config->getString( ApplicationConfigKey::LAYOUT_DEFAULT_TEMPLATE );
		$layoutSuffix = $this->config->getString( ApplicationConfigKey::LAYOUT_TEMPLATE_SUFFIX );

		$options = new ViewRendererOptions();
		$options->setViewTemplatePath( $viewPath );
		$options->setViewTemplateSuffix( $viewSuffix );

		$options->setLayoutDefaultTemplate( $layoutTemplate );
		$options->setLayoutTemplatePath( $layoutPath );
		$options->setLayoutTemplateSuffix( $layoutSuffix );

		return new ViewRenderer( $options );
	}

	/**
	 * Initializes the main controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ViewRenderer $renderer
	 *
	 * @return Controller
	 */
	private function initController( WebRequest $request, WebResponse $response, ViewRenderer $renderer ) {
		$controllerClassSuffix = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_SUFFIX );
		$controllerNamespaces = $this->config->getArray( ApplicationConfigKey::CONTROLLER_CLASS_NAMESPACES );
		$controllerClassName = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_MAIN );

		$controllerLoader = new ControllerLoader( $controllerNamespaces, $controllerClassSuffix );
		$controller = new $controllerClassName( $request, $response, $renderer, $controllerLoader );

		if ( $this->config->getBoolean( ApplicationConfigKey::LAYOUT_ENABLED ) ) {
			$controller->setLayout( new Layout() );
		}

		return $controller;
	}

	/**
	 * Retrieves the main controller.
	 *
	 * @return Controller
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