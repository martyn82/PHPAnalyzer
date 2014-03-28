<?php
namespace Controller;

use Mend\Data\DataPage;
use Mend\Data\Repository;
use Mend\Data\SortDirection;
use Mend\Data\SortOptions;
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
use Mend\Data\Storage\FileStorage;

class ProjectsController extends ResourceController {
	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * Constructs a new ProjectController instance.
	 *
	 * @param WebRequest $request
	 * @param WebResponse $response
	 * @param ControllerFactory $factory
	 * @param ViewRenderer $renderer
	 * @param Context $context
	 * @param Repository $repository
	 */
	public function __construct(
		WebRequest $request,
		WebResponse $response,
		ControllerFactory $factory,
		ViewRenderer $renderer,
		Context $context,
		Repository $repository
	) {
		parent::__construct( $request, $response, $factory, $renderer, $context );
		$this->repository = $repository;
	}

	/**
	 * @see ResourceController::actionIndex()
	 */
	public function actionIndex() {
// 		$projectRepository = $this->repository;

// 		$sortOptions = new SortOptions();
// 		$sortOptions->addSortField( 'key', SortDirection::ASCENDING );

// 		$dataPage = new DataPage( self::RESULTS_PER_PAGE, $this->getOffset() );
// 		$results = $projectRepository->all( $sortOptions, $dataPage );

// 		$result = new ResourceResult(
// 			array_map(
// 				function ( Project $record ) {
// 					return $record->toArray();
// 				},
// 				$results->toArray()
// 			),
// 			$this->getPageNumber(),
// 			$results->getTotalCount(),
// 			self::RESULTS_PER_PAGE
// 		);

// 		$this->setResult( $result );
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
