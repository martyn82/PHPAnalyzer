<?php
namespace Mend\Mvc\Rest;

use Mend\Mvc\Controller\Controller;

abstract class ResourceController extends Controller {
	/**
	 * Resource index action.
	 */
	abstract public function actionIndex();

	/**
	 * Resource create action.
	 */
	abstract public function actionCreate();

	/**
	 * Resource update action.
	 */
	abstract public function actionUpdate();

	/**
	 * Resource delete action.
	 */
	abstract public function actionDelete();

	/**
	 * Resource read action.
	 */
	abstract public function actionRead();
}
