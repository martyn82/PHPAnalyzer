<?php
namespace Mend\Rest;

use Mend\Mvc\Controller\PageController;

abstract class ResourceController extends PageController {
	/**
	 * Action to list all resources.
	 */
	abstract public function actionIndex();

	/**
	 * Action to read a single resource.
	 */
	abstract public function actionRead();

	/**
	 * Action to create a new resource.
	 */
	abstract public function actionCreate();

	/**
	 * Action to update a single resource.
	 */
	abstract public function actionUpdate();

	/**
	 * Action to delete a single resource.
	 */
	abstract public function actionDelete();
}
