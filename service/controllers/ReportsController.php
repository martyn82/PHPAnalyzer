<?php
namespace Controller;

use Mend\Collections\Map;
use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortDirection;
use Mend\Data\SortOptions;
use Mend\Data\Storage\FileStorage;
use Mend\Data\Storage\Handler\DefaultFileStorageHandler;
use Mend\Data\Storage\Handler\EntityMap;
use Mend\Data\Storage\Record;
use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Project\ProjectReport;
use Mend\Mvc\Context;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\HttpStatus;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Rest\ResourceController;
use Mend\Rest\ResourceResult;

use Model\Report\Report;
use Model\Report\ReportRepository;
use Model\Report\ReportMapper;

class ReportsController extends ResourceController {
	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
		throw new \RuntimeException( "Invalid action 'index' for resource 'reports'.", HttpStatus::STATUS_BAD_REQUEST );
	}

	/**
	 * @see ResourceController::actionRead()
	 */
	public function actionRead() {
		$repository = $this->getRepository();

		$criteria = new Map( array( 'id' => $this->getResourceId() ) );
		$sortOptions = new SortOptions();
		$sortOptions->addSortField( 'dateTime', SortDirection::ASCENDING );

		$reports = $repository->matching( $criteria, $sortOptions, new DataPage() );

		$result = new ResourceResult(
			array_map(
				function ( Report $report ) {
					return array( 'report' => $report->toArray() );
				},
				$reports->toArray()
			)
		);

		$this->setResult( $result );
	}

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

	/**
	 * Sets the repository.
	 *
	 * @param Repository $repository
	 */
	public function setRepository( Repository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * @return Repository
	 */
	private function getRepository() {
		if ( is_null( $this->repository ) ) {
			$entities = new EntityMap(
				array(
					'report' => new Directory( 'data/reports' )
				)
			);

			$handler = new DefaultFileStorageHandler( $entities );
			$storage = new FileStorage( $handler );
			$mapper = new ReportMapper( $storage );
			$this->repository = new ReportRepository( $mapper );
		}

		return $this->repository;
	}
}
