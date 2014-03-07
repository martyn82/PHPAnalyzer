<?php
namespace Controller;

use Mend\Mvc\View;
use Mend\Mvc\View\Layout;
use Mend\Mvc\Rest\RestResult;
use Mend\Mvc\Rest\ResourceController;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Metrics\Project\Project;
use Mend\IO\FileSystem\Directory;

class ProjectsController extends ResourceController {
	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
		return new RestResult( array() );
	}

	/**
	 * @see ResourceController::actionRead()
	 */
	public function actionRead() {}

	/**
	 * @see ResourceController::actionCreate()
	 */
	public function actionCreate() {}

	/**
	 * @see ResourceController::actionUpdate()
	 */
	public function actionUpdate() {}

	/**
	 * @see ResourceController::actionDelete()
	 */
	public function actionDelete() {}
}
