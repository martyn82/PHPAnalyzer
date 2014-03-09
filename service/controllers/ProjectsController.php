<?php
namespace Controller;

use Mend\Data\Page;
use Mend\Data\SortDirection;
use Mend\Data\SortOptions;
use Mend\Rest\ResourceController;
use Mend\Rest\ResourceResult;

use Repository\ProjectRepository;
use Record\ProjectRecord;

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
