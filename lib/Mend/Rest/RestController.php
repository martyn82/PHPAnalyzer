<?php
namespace Mend\Rest;

use Mend\Mvc\Controller\FrontController;
use Mend\Network\Web\HttpMethod;

class RestController extends FrontController {
	/**
	 * @see FrontController::dispatchRequest()
	 *
	 * @throws \Exception
	 */
	public function dispatchRequest() {
		$controllerName = $this->getControllerName();
		$actionName = null;

		$request = $this->getRequest();
		$method = $request->getMethod();

		switch ( $method ) {
			case HttpMethod::METHOD_DELETE:
				$actionName = RestAction::ACTION_DELETE;
				break;

			case HttpMethod::METHOD_GET:
				$actionName = ( $this->getActionName() == null )
					? RestAction::ACTION_INDEX
					: RestAction::ACTION_READ;
				break;

			case HttpMethod::METHOD_POST:
				$actionName = RestAction::ACTION_CREATE;
				break;

			case HttpMethod::METHOD_PUT:
			case HttpMethod::METHOD_PATCH:
				$actionName = RestAction::ACTION_UPDATE;
				break;

			default:
				throw new \Exception( "Invalid method: '{$method}'." );
		}

		$this->dispatch( $controllerName, $actionName );
	}

	/**
	 * @see FrontController::parseRequest()
	 */
	protected function parseRequest( $defaultController = 'index', $defaultAction = null ) {
		parent::parseRequest( $defaultController, $defaultAction );
	}

	/**
	 * @see FrontController::createController()
	 */
	protected function createController( $controllerName ) {
		/* @var $controller ResourceController */
		$controller = parent::createController( $controllerName );
		$controller->setResourceId( $this->getActionName() );
		return $controller;
	}
}
