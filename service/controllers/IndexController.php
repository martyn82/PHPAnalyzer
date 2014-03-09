<?php
namespace Controller;

use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\HttpStatus;

class IndexController extends PageController {
	/**
	 * @see PageController::init()
	 */
	protected function init() {
		$this->getViewRenderer()->disable();
	}

	/**
	 * Index action.
	 */
	public function actionIndex() {
		$response = $this->getResponse();

		$response->getHeaders()->set( 'Content-Type', 'text/plain' );
		$response->setStatusCode( HttpStatus::STATUS_BAD_REQUEST );
		$response->setStatusDescription( "Bad request" );
		$response->setBody( "No resource specified." );
	}
}