<?php
namespace Mend\Metrics\Report;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\Builder\ComplexityReportBuilder;
use Mend\Metrics\Report\Builder\EntityReportBuilder;
use Mend\Metrics\Report\Builder\UnitSizeReportBuilder;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\Report\Builder\VolumeReportBuilder;
use Mend\Metrics\Report\Builder\DuplicationReportBuilder;

class ProjectReportBuilder {
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var ProjectReport
	 */
	private $report;

	/**
	 * Constructs a new project report builder.
	 *
	 * @param Project $project
	 */
	public function __construct( Project $project ) {
		$this->project = $project;
		$this->report = new ProjectReport( $project );
	}

	/**
	 * Retrieves the project.
	 *
	 * @return Project
	 */
	protected function getProject() {
		return $this->project;
	}

	/**
	 * Retrieves the built report.
	 *
	 * @return ProjectReport
	 */
	public function getReport() {
		return $this->report;
	}

	/**
	 * Retrieves the entity report.
	 *
	 * @return EntityReport
	 */
	private function getEntityReport() {
		if ( !$this->report->hasReport( ReportType::REPORT_ENTITY ) ) {
			$this->report->addReport( ReportType::REPORT_ENTITY, new EntityReport() );
		}

		return $this->report->getReport( ReportType::REPORT_ENTITY );
	}

	/**
	 * Extracts entities from project.
	 *
	 * @return ProjectReportBuilder
	 */
	public function extractEntities() {
		$entityBuilder = new EntityReportBuilder( $this->getProject() );
		$entityBuilder->extractEntities();

		$report = $entityBuilder->getReport();
		$this->report->addReport( ReportType::REPORT_ENTITY, $report );

		return $this;
	}

	/**
	 * Extracts volume facts for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function extractVolume() {
		$volumeBuilder = new VolumeReportBuilder( $this->getProject() );
		$volumeBuilder->extractVolume();

		$report = $volumeBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_VOLUME, $report );

		return $this;
	}

	/**
	 * Analyzes complexity for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function analyzeComplexity() {
		$complexityBuilder = new ComplexityReportBuilder( $this->getProject() );
		$complexityBuilder->analyzeComplexity( $this->getEntityReport() );

		$report = $complexityBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_COMPLEXITY, $report );

		return $this;
	}

	/**
	 * Analyzes unit size for the current report.
	 *
	 * @return ProjectReportBuilder
	 */
	public function analyzeUnitSize() {
		$unitSizeBuilder = new UnitSizeReportBuilder( $this->getProject() );
		$unitSizeBuilder->analyzeUnitSize( $this->getEntityReport() );

		$report = $unitSizeBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_UNITSIZE, $report );

		return $this;
	}

	/**
	 * Computes duplicated code blocks.
	 *
	 * @return ProjectReportBuilder
	 */
	public function computeDuplications() {
		$projectReport = $this->getReport();
		$volumeReport = $projectReport->getReport( ReportType::REPORT_VOLUME );

		$duplicationBuilder = new DuplicationReportBuilder( $this->getProject() );
		$duplicationBuilder->computeDuplications( $volumeReport );

		$report = $duplicationBuilder->getReport();
		$projectReport->addReport( ReportType::REPORT_DUPLICATION, $report );

		return $this;
	}
}