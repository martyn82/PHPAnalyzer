<?php
namespace Controller;

use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortDirection;
use Mend\Data\SortOptions;
use Mend\Data\Storage\FileStorage;
use Mend\IO\FileSystem\Directory;
use Mend\Metrics\Project\ProjectReport;
use Mend\Mvc\Context;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View\ViewRenderer;
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Rest\ResourceController;
use Mend\Rest\ResourceResult;

use Model\Project\Project;
use Model\Project\ProjectRepository;
use Model\Project\ProjectMapper;
use Mend\Data\Storage\Handler\DefaultFileStorageHandler;
use Mend\Data\Storage\Handler\EntityMap;
use Mend\Data\Storage\Record;

class ProjectsController extends ResourceController {
	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
		$projectRepository = $this->getRepository();

		$sortOptions = new SortOptions();
		$sortOptions->addSortField( 'key', SortDirection::ASCENDING );

		$dataPage = new DataPage( self::RESULTS_PER_PAGE, $this->getOffset() );
		$results = $projectRepository->all( $sortOptions, $dataPage );

		$result = new ResourceResult(
			array_map(
				function ( Project $project ) {
					return $project->toArray();
				},
				$results->toArray()
			),
			$this->getPageNumber(),
			$results->getTotalCount(),
			self::RESULTS_PER_PAGE
		);

		$this->setResult( $result );
	}

	/**
	 * @see ResourceController::actionRead()
	 */
	public function actionRead() {
		$projectRepository = $this->getRepository();
		$project = $projectRepository->get( $this->getResourceId() );

		$result = new ResourceResult(
			array(
				'project' => $project->toArray(),
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
					'project' => new Directory( 'data/projects' )
				)
			);

			$handler = new DefaultFileStorageHandler( $entities );
			$storage = new FileStorage( $handler );
			$mapper = new ProjectMapper( $storage );
			$this->repository = new ProjectRepository( $mapper );
		}

		return $this->repository;
	}
}
