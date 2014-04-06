<?php
namespace Mend\Rest;

use Mend\Data\Repository;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\Controller\PageController;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;

abstract class ResourceController extends PageController {
	const PARAMETER_PAGE = 'page';
	const FIRST_PAGE = 1;
	const RESULTS_PER_PAGE = 10;

	/**
	 * @var string
	 */
	private $resourceId;

	/**
	 * @var ResourceResult
	 */
	private $result;

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

	/**
	 * @see PageController::render()
	 */
	protected function render() {
		$result = $this->getResult();

		$response = array(
			'totalResults' => $result->getTotalResultsCount(),
			'page' => $result->getPageNumber(),
			'itemsPerPage' => $result->getResultsPerPage(),
			'results' => $result->getData()
		);

		return json_encode( $response, JSON_NUMERIC_CHECK );
	}

	/**
	 * Sets the resource ID.
	 *
	 * @param string $id
	 */
	public function setResourceId( $id ) {
		$this->resourceId = $id;
	}

	/**
	 * Retrieves the resource ID.
	 *
	 * @return string
	 */
	public function getResourceId() {
		return $this->resourceId;
	}

	/**
	 * Sets the resource result instance.
	 *
	 * @param ResourceResult $result
	 */
	protected function setResult( ResourceResult $result ) {
		$this->result = $result;
	}

	/**
	 * Retrieves the resource result.
	 *
	 * @return ResourceResult
	 */
	protected function getResult() {
		return $this->result ? : new ResourceResult( array() );
	}

	/**
	 * Retrieves the requested page number.
	 *
	 * @return integer
	 */
	protected function getPageNumber() {
		$request = $this->getRequest();
		$parameters = $request->getParameters();

		return (int) $parameters->get( self::PARAMETER_PAGE, self::FIRST_PAGE );
	}

	/**
	 * Retrieves the offset for a list of resources based on requested page parameter.
	 *
	 * @return integer
	 */
	protected function getOffset() {
		$page = $this->getPageNumber();
		return self::RESULTS_PER_PAGE * ( $page - 1 );
	}
}
