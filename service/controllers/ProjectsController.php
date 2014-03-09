<?php
namespace Controller;

use Mend\Data\Page;
use Mend\Data\SortDirection;
use Mend\Data\SortOptions;
use Mend\IO\FileSystem\Directory;
use Mend\Rest\ResourceController;
use Mend\Rest\ResourceResult;

use Record\ProjectRecord;
use Repository\ProjectRepository;
use Mend\Metrics\Project\ProjectReport;

class ProjectsController extends ResourceController {
	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
		$projectRepository = new ProjectRepository();

		$sortOptions = new SortOptions();
		$sortOptions->addSortField( 'key', SortDirection::ASCENDING );

		$page = new Page( self::RESULTS_PER_PAGE, $this->getOffset() );

		$totalCount = 0;
		$results = $projectRepository->all( $sortOptions, $page, $totalCount );

		$result = new ResourceResult(
			array_map(
				function ( ProjectRecord $record ) {
					return $record->toArray();
				},
				$results
			),
			$this->getPageNumber(),
			$totalCount,
			self::RESULTS_PER_PAGE
		);

		$this->setResult( $result );
	}

	/**
	 * @see ResourceController::actionRead()
	 */
	public function actionRead() {
		$projectRepository = new ProjectRepository();
		$project = $projectRepository->get( $this->getResourceId() );

		$result = new ResourceResult(
			array(
				'project' => $project->toArray(),
				'reports' => $project->reports
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
}
