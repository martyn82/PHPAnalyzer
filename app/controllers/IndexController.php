<?php
namespace Controller;

use \MVC\Controller;

class IndexController extends Controller {
	/**
	 * Pre dispatch.
	 */
	protected function preDispatch() {
		parent::preDispatch();

		$layout = $this->getLayout();
		$layout->setTitle( 'PHP Analyzer');
	}

	/**
	 * Index action.
	 */
	public function actionIndex() {
	}
}
