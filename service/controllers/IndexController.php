<?php
namespace Controller;

use Mend\Mvc\Controller\Controller;
use Mend\Network\Web\HttpStatus;

class IndexController extends Controller {
	/**
	 * Index action.
	 */
	public function actionIndex() {
		$this->enableRenderer( false );

		$response = $this->getResponse();
		$response->setStatusCode( HttpStatus::STATUS_BAD_REQUEST );
	}
}