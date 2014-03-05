<?php
namespace Mend;

use Mend\Config\ConfigProvider;
use Mend\Mvc\Controller\ControllerLoader;
use Mend\Mvc\Controller\FrontController;
use Mend\Mvc\View\Layout;
use Mend\Mvc\View\ViewRenderer;
use Mend\Mvc\View\ViewRendererOptions;
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

		$options = $this->createViewRendererOptions();
		$renderer = $this->createViewRenderer( $options );
		$loader = $this->createControllerLoader();

		$this->controller = $this->createController( $request, $response, $renderer, $loader );
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
	 * Initializes a ViewRendererOptions instance.
	 *
	 * @return ViewRendererOptions
	 */
	protected function createViewRendererOptions() {
		return new ViewRendererOptions();
	}

	/**
	 * Initializes the view renderer.
	 *
	 * @param ViewRendererOptions $options
	 *
	 * @return ViewRenderer
	 */
	protected function createViewRenderer( ViewRendererOptions $options ) {
		$viewPath = realpath( $this->config->getString( ApplicationConfigKey::VIEW_PATH ) );
		$viewSuffix = $this->config->getString( ApplicationConfigKey::VIEW_TEMPLATE_SUFFIX );

		$layoutPath = realpath( $this->config->getString( ApplicationConfigKey::LAYOUT_PATH ) );
		$layoutTemplate = $this->config->getString( ApplicationConfigKey::LAYOUT_DEFAULT_TEMPLATE );
		$layoutSuffix = $this->config->getString( ApplicationConfigKey::LAYOUT_TEMPLATE_SUFFIX );

		$options->setViewTemplatePath( $viewPath );
		$options->setViewTemplateSuffix( $viewSuffix );

		$options->setLayoutDefaultTemplate( $layoutTemplate );
		$options->setLayoutTemplatePath( $layoutPath );
		$options->setLayoutTemplateSuffix( $layoutSuffix );

		return new ViewRenderer( $options );
	}

	/**
	 * Initializes the controller loader.
	 *
	 * @return ControllerLoader
	 */
	protected function createControllerLoader() {
		$controllerClassSuffix = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_SUFFIX );
		$controllerNamespaces = $this->config->getArray( ApplicationConfigKey::CONTROLLER_CLASS_NAMESPACES );

		return new ControllerLoader( $controllerNamespaces, $controllerClassSuffix );
	}

	/**
	 * Initializes the main controller.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ViewRenderer $renderer
	 * @param ControllerLoader $loader
	 *
	 * @return FrontController
	 */
	protected function createController(
		WebRequest $request,
		WebResponse $response,
		ViewRenderer $renderer,
		ControllerLoader $loader
	) {
		$controllerClassName = $this->config->getString( ApplicationConfigKey::CONTROLLER_CLASS_MAIN );
		$controller = new $controllerClassName( $request, $response, $renderer, $loader );

		assert( $controller instanceof FrontController );

		if ( $this->config->getBoolean( ApplicationConfigKey::LAYOUT_ENABLED ) ) {
			$controller->setLayout( new Layout() );
		}

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