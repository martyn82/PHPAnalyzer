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
		$request = $this->getRequest();
		$parameters = $request->getParameters();

		if ( !$parameters->hasKey( 'project' ) ) {
			throw new \RuntimeException( "No project parameter specified." );
		}

		$projectId = $parameters->get( 'project' );
		$view = $this->getView();
		$view->assign( 'project', $projectId );
	}
}
