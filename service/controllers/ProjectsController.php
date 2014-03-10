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
use Mend\Network\Web\WebRequest;
use Mend\Network\Web\WebResponse;
use Mend\Mvc\ControllerFactory;
use Mend\Mvc\View\ViewRenderer;
use Mend\Mvc\Context;
use Mend\Data\Repository;

class ProjectsController extends ResourceController {
	private $repository;

	public function __construct(
		WebRequest $request,
		WebResponse $response,
		ControllerFactory $factory,
		ViewRenderer $renderer,
		Context $context,
		Repository $repository = null
	) {
		parent::__construct( $request, $response, $factory, $renderer, $context );
		$this->repository = $repository ? : new ProjectRepository();
	}

	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
		$projectRepository = $this->repository;

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
		$projectRepository = $this->repository;
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
