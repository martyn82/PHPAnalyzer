<?php
namespace Controller;

use Mend\Mvc\Controller\PageController;

class IndexController extends PageController {
	/**
	 * Pre dispatch.
	 */
	protected function preDispatch() {
		parent::preDispatch();

		$layout = $this->getLayout();

		if ( !is_null( $layout ) ) {
			$layout->setTitle( 'PHP Analyzer');
		}
	}

	/**
	 * Index action.
	 */
	public function actionIndex() {
	}
}
