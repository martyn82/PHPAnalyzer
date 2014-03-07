<?php
namespace Mend\Mvc\Rest;

use Mend\Mvc\Controller;

abstract class ResourceController extends Controller {
	/**
	 * Resource index action.
	 *
	 * @return RestResponse
	 */
	abstract public function actionIndex();

	/**
	 * Resource create action.
	 *
	 * @return RestResponse
	 */
	abstract public function actionCreate();

	/**
	 * Resource update action.
	 *
	 * @return RestResponse
	 */
	abstract public function actionUpdate();

	/**
	 * Resource delete action.
	 *
	 * @return RestResponse
	 */
	abstract public function actionDelete();

	/**
	 * Resource read action.
	 *
	 * @return RestResponse
	 */
	abstract public function actionRead();
}
