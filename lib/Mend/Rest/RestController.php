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
				throw new \Exception( "Invalid method: '{$method}'." );
		}

		$this->dispatch( $controllerName, $actionName );
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
