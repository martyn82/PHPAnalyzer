<?php
namespace Controller;

use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\HttpStatus;

class IndexController extends PageController {
	/**
	 * Index action.
	 */
	public function actionIndex() {
		$this->enableRender( false );

		$response = $this->getResponse();
		$response->setStatusCode( HttpStatus::STATUS_BAD_REQUEST );
	}
}