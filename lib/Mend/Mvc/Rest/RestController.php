<?php
namespace Mend\Mvc\Rest;

use Mend\Mvc\Controller\ControllerException;
use Mend\Mvc\Controller\FrontController;
use Mend\Network\Web\HttpMethod;
use Mend\Mvc\Controller\ActionResult;

class RestController extends FrontController {
	/**
	 * @see FrontController::dispatchRequest()
	 *
	 * @throws ControllerException
	 *
	 * @return RestResponse
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
	 * @see Controller::postDispatch()
	 */
	public function postDispatch() {
		$result = $this->getActionResult();

		$responseBody = array(
			'page' => $result->getPageNumber(),
			'resultsPerPage' => $result->getResultsPerPage(),
			'totalResults' => $result->getTotalResultsCount(),
			'data' => $result->getData()
		);

		$body = json_encode( array( 'response' => $responseBody ), JSON_NUMERIC_CHECK );

		$response = $this->getResponse();
		$headers = $response->getHeaders();

		$headers->set( 'Content-Type', 'application/json' );
		$headers->set( 'Content-Length', strlen( $body ) );

		$response->setBody( $body );
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

	/**
	 * @see Controller::setActionResult()
	 */
	protected function setActionResult( ActionResult $result = null ) {
		$result ? : new RestResult( array() );
		parent::setActionResult( $result );
	}

	/**
	 * @see Controller::getActionResult()
	 */
	protected function getActionResult() {
		$result = parent::getActionResult();
		return $result ? : new RestResult( array() );
	}
}
