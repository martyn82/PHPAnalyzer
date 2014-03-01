<?php
namespace Mend\Mvc\Controller\Rest;

use Mend\Mvc\Controller\FrontController;
use Mend\Network\Web\HttpMethod;
use Mend\Mvc\ControllerException;

class RestController extends FrontController {
	/**
	 * @see FrontController::dispatchRequest()
	 *
	 * @throws ControllerException
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
				$actionName = ( $this->getResourceId() == null )
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
				throw new ControllerException( "Invalid method: '{$method}'." );
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
	 * Retrieves the resource ID.
	 *
	 * @return integer
	 */
	protected function getResourceId() {
		$actionName = $this->getActionName();

		if ( is_numeric( $actionName ) ) {
			return (int) $actionName;
		}

		return null;
	}
}
