<?php
namespace Mend\Metrics\Report;

use Mend\Metrics\Complexity\ComplexityAnalyzer;
use Mend\Metrics\Project\Project;
use Mend\Metrics\Project\ProjectReport;
use Mend\Metrics\Report\Builder\ComplexityReportBuilder;
use Mend\Metrics\Report\Builder\EntityReportBuilder;
use Mend\Metrics\Report\Builder\UnitSizeReportBuilder;
use Mend\Metrics\Report\Builder\VolumeReportBuilder;
use Mend\Metrics\Report\Builder\DuplicationReportBuilder;
use Mend\Metrics\UnitSize\UnitSizeAnalyzer;
use Mend\Metrics\Volume\VolumeReport;

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
	 * @var array
	 */
	private $fileExtensions;

	/**
	 * Constructs a new project report builder.
	 *
	 * @param Project $project
	 * @param array $extensions
	 */
	public function __construct( Project $project, array $extensions = null ) {
		$this->project = $project;
		$this->fileExtensions = $extensions;
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
	 * Retrieves the file extensions.
	 *
	 * @return array
	 */
	private function getFileExtensions() {
		return $this->fileExtensions;
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
	 * Retrieves the volume report.
	 *
	 * @return VolumeReport
	 */
	private function getVolumeReport() {
		if ( !$this->report->hasReport( ReportType::REPORT_VOLUME ) ) {
			$this->report->addReport( ReportType::REPORT_VOLUME, new VolumeReport() );
		}

		return $this->report->getReport( ReportType::REPORT_VOLUME );
	}

	/**
	 * Extracts entities from project.
	 *
	 * @return ProjectReportBuilder
	 */
	public function extractEntities() {
		$entityBuilder = new EntityReportBuilder( $this->getProject(), $this->getFileExtensions() );
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
		$volumeBuilder = new VolumeReportBuilder( $this->getProject(), $this->getFileExtensions() );
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
		$complexityBuilder = new ComplexityReportBuilder( $this->getProject(), $this->getFileExtensions() );
		$complexityBuilder->analyzeComplexity( $this->getEntityReport(), $this->getVolumeReport() );

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
		$unitSizeBuilder = new UnitSizeReportBuilder( $this->getProject(), $this->getFileExtensions() );
		$unitSizeBuilder->analyzeUnitSize( $this->getEntityReport(), $this->getVolumeReport() );

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
		$duplicationBuilder = new DuplicationReportBuilder( $this->getProject(), $this->getFileExtensions() );
		$duplicationBuilder->computeDuplications( $this->getVolumeReport() );

		$report = $duplicationBuilder->getReport();
		$this->getReport()->addReport( ReportType::REPORT_DUPLICATION, $report );

		return $this;
	}
}