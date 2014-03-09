<?php
namespace Controller;

use Mend\Mvc\Context\TextContext;
use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\HttpStatus;

class IndexController extends PageController {
	/**
	 * @see PageController::init()
	 */
	protected function init() {
		$this->getViewRenderer()->disable();
		$this->setContext( new TextContext() );
	}

	/**
	 * Index action.
	 */
	public function actionIndex() {
		$response = $this->getResponse();

		$response->setStatusCode( HttpStatus::STATUS_BAD_REQUEST );
		$response->setStatusDescription( "Bad request" );
		$response->setBody( "No resource specified." );
	}
}